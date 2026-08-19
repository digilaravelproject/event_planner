<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AiSetting;
use App\Models\EventRequirementQuestion;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Services\PlanPresentationService;
use Database\Seeders\EventRequirementQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EventPlanningFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_and_planner_use_managed_wedding_questions(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        EventRequirementQuestion::where('question_code', 'food_type')->update([
            'options' => ['Paneer Tikka', 'Gulab Jamun'],
            'vendor_attribute_values' => ['Paneer Tikka', 'Gulab Jamun'],
            'option_metadata' => [
                'Paneer Tikka' => ['label' => 'Paneer Tikka', 'category' => 'Starters', 'cost' => 125],
                'Gulab Jamun' => ['label' => 'Gulab Jamun', 'category' => 'Desserts', 'cost' => 50],
            ],
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Grand Wedding', false)
            ->assertSeeText('150 Guests');

        $this->get(route('ai-planner', ['type' => 'wedding', 'guests' => 300]))
            ->assertOk()
            ->assertSee('What is your total estimated wedding budget?')
            ->assertSee('How many guests will celebrate with you?')
            ->assertSee('foodItems: []', false)
            ->assertSee('answers[food_menu_items]', false)
            ->assertSee('plannerSteps:', false)
            ->assertSee('totalSteps:', false)
            ->assertDontSee('currentStep === 5 && this.planner.foodItems', false);
    }

    public function test_guest_requirements_resume_after_login_and_create_history_with_suggestions(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        EventRequirementQuestion::where('question_code', 'food_type')->update([
            'options' => ['Paneer Tikka', 'Gulab Jamun'],
            'vendor_attribute_values' => ['Paneer Tikka', 'Gulab Jamun'],
            'option_metadata' => [
                'Paneer Tikka' => ['label' => 'Paneer Tikka', 'category' => 'Starters', 'cost' => 125],
                'Gulab Jamun' => ['label' => 'Gulab Jamun', 'category' => 'Desserts', 'cost' => 50],
            ],
        ]);
        $user = User::factory()->create(['status' => true, 'password' => 'password']);
        AiSetting::setValue('openrouter_api_key', Crypt::encryptString('test-key'));
        AiSetting::setValue('openrouter_model', 'openrouter/auto');

        Http::fake([
            '*/chat/completions' => Http::response([
                'choices' => [['message' => ['content' => json_encode([
                    'title' => 'AI Wedding Plan',
                    'overview' => 'A practical wedding plan.',
                    'total_cost' => 2500000,
                    'costing' => [
                        ['category' => 'Venue', 'amount' => 1000000, 'percentage' => 40, 'summary' => 'Venue package', 'vendor_ids' => []],
                        ['category' => 'Catering', 'amount' => 1500000, 'percentage' => 60, 'summary' => 'Guest catering', 'vendor_ids' => []],
                    ],
                    'recommendations' => [],
                    'notes' => ['Confirm availability.'],
                ])]]],
            ]),
        ]);

        $payload = [
            'category' => 'wedding',
            'guest_count' => 200,
            'answers' => [
                'wedding_budget' => 25,
                'guest_capacity' => 200,
                'wedding_tradition' => 'Maharashtrian Lagna',
                'food_menu_items' => [
                    ['id' => 'Paneer Tikka', 'title' => 'Paneer Tikka', 'category' => 'Starters', 'cost' => 125],
                    ['id' => 'Gulab Jamun', 'title' => 'Gulab Jamun', 'category' => 'Desserts', 'cost' => 50],
                ],
            ],
        ];

        $this->post(route('ai-planner.generate'), $payload)
            ->assertRedirect(route('user.login'))
            ->assertSessionHas('pending_event_plan')
            ->assertSessionHas('pending_event_plan.answers.food_menu_items.0.title', 'Paneer Tikka');

        $this->post(route('user.login.submit'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('ai-planner.resume'));

        $response = $this->actingAs($user)->get(route('ai-planner.resume'));
        $plan = UserEventPlan::whereNull('parent_plan_id')->firstOrFail();
        $response->assertRedirect(route('user.plans.show', $plan));
        $this->assertCount(6, $plan->suggestions);
        $this->assertSame('5 saved requirements', data_get($plan->summary, 'display_content.selection_eyebrow'));
        $this->assertSame('Essential option', data_get($plan->suggestions->firstWhere('title', 'Essential Wedding Plan')?->summary, 'comparison.tier'));
        $catering = collect($plan->summary['costing'])->firstWhere('category', 'Catering');
        $this->assertSame(35000.0, (float) $catering['amount']);
        $this->assertSame(['Paneer Tikka', 'Gulab Jamun'], array_column($catering['attributes'], 'name'));

        $this->actingAs($user)->get(route('user.plans.index'))
            ->assertOk()->assertSee('AI Wedding Plan');
        $this->actingAs($user)->get(route('user.plans.show', $plan))
            ->assertOk()->assertSee('Nearby Value Plan')->assertSee('Nearby Premium Plan');
    }

    public function test_user_cannot_view_another_users_plan(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $plan = UserEventPlan::create([
            'user_id' => $owner->id,
            'title' => 'Private plan',
            'category' => 'wedding',
            'guest_count' => 100,
            'answers' => [],
            'requirement_prompt' => 'prompt',
            'summary' => ['total_cost' => 100, 'costing' => []],
            'total_cost' => 100,
            'status' => 'completed',
        ]);

        $this->actingAs($other)->get(route('user.plans.show', $plan))->assertForbidden();
    }

    public function test_legacy_fallback_plan_shows_database_vendors_without_static_allocations(): void
    {
        $user = User::factory()->create();
        $plan = UserEventPlan::create([
            'user_id' => $user->id,
            'title' => 'Readable vendor plan',
            'category' => 'wedding',
            'guest_count' => 300,
            'answers' => ['wedding_budget' => 25],
            'requirement_prompt' => 'prompt',
            'vendor_snapshot' => [
                ['id' => 1, 'name' => 'Royal Grand Hotel', 'category' => 'Hotel', 'attributes' => [['key' => 'price', 'value' => 500000]]],
                ['id' => 2, 'name' => 'Celebration Caterers', 'category' => 'Catering', 'attributes' => [['key' => 'price', 'value' => 300000]]],
            ],
            'summary' => [
                'title' => 'Readable vendor plan',
                'overview' => 'Saved plan overview.',
                'costing' => [
                    ['category' => 'Venue & Stay', 'amount' => 500000, 'percentage' => 62.5, 'summary' => 'Venue total', 'vendor_ids' => [], 'attributes' => []],
                    ['category' => 'Catering & Service', 'amount' => 300000, 'percentage' => 37.5, 'summary' => 'Catering total', 'vendor_ids' => [], 'attributes' => []],
                ],
            ],
            'total_cost' => 800000,
            'status' => 'completed',
        ]);

        $presentation = app(PlanPresentationService::class)->present($plan);
        $this->assertSame(['Royal Grand Hotel', 'Celebration Caterers'], array_column($presentation['plan_vendors'], 'name'));

        $this->actingAs($user)->get(route('user.plans.show', $plan))
            ->assertOk()
            ->assertSee('Vendors matched to this plan')
            ->assertSee('Royal Grand Hotel')
            ->assertSee('Celebration Caterers')
            ->assertDontSee('Indicative allocation');
    }

    public function test_generation_screen_is_viewport_fixed_and_staged(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);

        $this->get(route('ai-planner', ['type' => 'wedding', 'guests' => 300]))
            ->assertOk()
            ->assertSee('x-teleport="body"', false)
            ->assertSee('fixed inset-0 z-[120]', false)
            ->assertSee('Crafting your celebration')
            ->assertSee('Matching vendors');
    }

    public function test_planner_exposes_option_images_and_custom_ceremony_choice(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        EventRequirementQuestion::where('question_code', 'wedding_tradition')->update([
            'options' => ['Garden Wedding', 'Palace Wedding'],
            'option_images' => ['question-options/garden.jpg', null],
        ]);

        $response = $this->get(route('ai-planner'))
            ->assertOk()
            ->assertSee('Add your own ceremony or custom choice')
            ->assertSee('addCustomCeremony()', false);

        $this->assertSame(
            asset('storage/question-options/garden.jpg'),
            data_get($response->viewData('plannerOptions'), 'wedding_tradition.images.0')
        );
    }

    public function test_admin_questions_drive_homepage_dropdowns_and_dynamic_planner_tabs(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        EventRequirementQuestion::where('question_code', 'event_category')->update([
            'options' => ['Wedding Celebration', 'Birthday Party'],
        ]);
        EventRequirementQuestion::where('question_code', 'guest_capacity')->update([
            'options' => ['500', '800', '1200', '1600'],
            'vendor_attribute_values' => ['50', '150', '300', '600'],
        ]);
        EventRequirementQuestion::where('question_code', 'food_type')->update([
            'options' => ['Butter Chicken', 'Masala Dosa'],
            'option_vendor_values' => ['food_1', 'food_2'],
            'vendor_attribute_values' => ['legacy_food_1', 'legacy_food_2'],
            'option_images' => ['question-options/butter-chicken.jpg', 'question-options/masala-dosa.jpg'],
            'option_metadata' => [
                'food_1' => ['category' => 'Main Course', 'cost' => 350],
                'food_2' => ['category' => 'Live Counters', 'cost' => 140],
            ],
        ]);
        EventRequirementQuestion::create([
            'question' => 'Which music style do you prefer?',
            'question_code' => 'music_style',
            'question_type' => 'radio',
            'options' => ['Live Band', 'DJ Night'],
            'option_images' => ['question-options/live-band.jpg', null],
            'option_metadata' => [
                'Live Band' => ['subtitle' => 'Live musicians for your celebration', 'icon' => 'fa-solid fa-music'],
                'DJ Night' => ['subtitle' => 'A high-energy dance experience', 'icon' => 'fa-solid fa-star'],
            ],
            'is_required' => true,
            'display_order' => 50,
            'status' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Birthday Party')
            ->assertSee('500 Guests')
            ->assertSee('1600 Guests');

        $response = $this->get(route('ai-planner', ['type' => 'Birthday Party', 'guests' => 1200]))
            ->assertOk()
            ->assertSee('Which music style do you prefer?')
            ->assertSee('Live Band')
            ->assertSee('DJ Night')
            ->assertSee('Live musicians for your celebration')
            ->assertSee('fa-solid fa-music', false)
            ->assertSee("cateringMode: 'custom'", false)
            ->assertSee('Admin Food Options')
            ->assertSee('answers[${step.code}]', false);

        $steps = collect($response->viewData('plannerSteps'));
        $plannerOptions = $response->viewData('plannerOptions');
        $this->assertSame('event_category', $steps->first()['code']);
        $this->assertSame(9, $steps->count());
        $this->assertSame('generic', $steps->firstWhere('code', 'music_style')['renderer']);
        $this->assertSame('Live musicians for your celebration', $steps->firstWhere('code', 'music_style')['option_details'][0]['subtitle']);
        $this->assertSame(['500', '800', '1200', '1600'], data_get($plannerOptions, 'guest_capacity.options'));
        $this->assertSame('Butter Chicken', data_get($plannerOptions, 'food_type.options.0.title'));
        $this->assertSame('food_1', data_get($plannerOptions, 'food_type.options.0.id'));
        $this->assertSame(asset('storage/question-options/butter-chicken.jpg'), data_get($plannerOptions, 'food_type.options.0.image'));
        $this->assertSame(
            asset('storage/question-options/live-band.jpg'),
            $steps->firstWhere('code', 'music_style')['images'][0]
        );
    }

    public function test_shared_navigation_links_return_to_homepage_sections(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);

        $this->get(route('ai-planner'))
            ->assertOk()
            ->assertSee(route('home').'#categories', false)
            ->assertSee(route('home').'#how-it-works', false)
            ->assertSee(route('home').'#estimator', false)
            ->assertSee(route('home').'#testimonials', false)
            ->assertDontSee('history.replaceState(null, null, window.location.pathname)', false);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="categories"', false)
            ->assertSee('id="site-footer"', false);
    }

    public function test_user_dashboard_and_pdf_download_are_available(): void
    {
        $user = User::factory()->create();
        $plan = $this->planFor($user);

        $this->actingAs($user)->get(route('user.dashboard'))
            ->assertOk()->assertSee('planning studio')->assertSee('Detailed plan', false);
        $this->actingAs($user)->get(route('user.plans.show', $plan))
            ->assertOk()
            ->assertSee('Detailed plan')
            ->assertSee('Food Type')
            ->assertSee('₹25 lakh')
            ->assertSee('Generate new plan')
            ->assertSee('Base Catering Service')
            ->assertDontSee('base_catering_service');

        $response = $this->actingAs($user)->get(route('user.plans.download', $plan));
        $response->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $response->getContent());
        $this->assertStringContainsString('DETAILED COSTING', $response->getContent());
        $this->assertStringNotContainsString('MATCHED VENDORS', $response->getContent());
        $this->assertStringNotContainsString('Royal Caterer', $response->getContent());
    }

    public function test_admin_can_open_user_plan_audit_and_download_pdf(): void
    {
        $admin = Admin::create(['name' => 'Planning Admin', 'email' => 'planning-admin@example.com', 'password' => 'password']);
        $user = User::factory()->create();
        $plan = $this->planFor($user);

        $this->actingAs($admin, 'admin')->get(route('admin.users.index'))
            ->assertOk()->assertSee(route('admin.users.plans.index', $user));
        $this->actingAs($admin, 'admin')->get(route('admin.users.plans.index', $user))
            ->assertOk()->assertSee('Detailed plan');
        $this->actingAs($admin, 'admin')->get(route('admin.users.plans.show', $plan))
            ->assertOk()->assertSee('AI Audit Details')->assertSee('private generated prompt');

        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.plans.download', $plan));
        $response->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('DETAILED COSTING', $response->getContent());
        $this->assertStringNotContainsString('AI REQUIREMENT PROMPT', $response->getContent());
        $this->assertStringNotContainsString('MATCHED VENDORS', $response->getContent());
        $this->assertStringNotContainsString('Royal Caterer', $response->getContent());
    }

    private function planFor(User $user): UserEventPlan
    {
        return UserEventPlan::create([
            'user_id' => $user->id,
            'title' => 'Detailed plan',
            'category' => 'wedding',
            'guest_count' => 200,
            'answers' => ['wedding_budget' => 25, 'food_type' => 'Vegetarian'],
            'requirement_prompt' => 'private generated prompt',
            'vendor_snapshot' => [['id' => 10, 'name' => 'Royal Caterer', 'category' => 'Catering', 'attributes' => ['menu' => 'Jain and Punjabi', 'service_area' => 'Mumbai']]],
            'summary' => [
                'title' => 'Detailed plan',
                'overview' => 'A deeply costed plan.',
                'total_cost' => 2500000,
                'costing' => [['category' => 'Catering', 'amount' => 750000, 'percentage' => 30, 'summary' => 'Food and service', 'vendor_ids' => [10], 'attributes' => [['name' => 'Base_Catering_Service', 'value' => 'main_course', 'cost' => 750000]]]],
                'recommendations' => [['vendor_id' => 10, 'name' => 'Royal Caterer', 'category' => 'Catering', 'reason' => 'Matches the menu.', 'estimated_cost' => 750000]],
                'notes' => ['Confirm availability.'],
            ],
            'total_cost' => 2500000,
            'model' => 'openrouter/auto',
            'status' => 'completed',
        ]);
    }
}
