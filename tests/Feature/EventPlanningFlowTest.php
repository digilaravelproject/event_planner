<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AiSetting;
use App\Models\EventRequirementQuestion;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Services\EventPlanningService;
use App\Services\OpenRouterService;
use App\Services\PlanPdfService;
use App\Services\PlanPresentationService;
use App\Services\VendorCostingService;
use Database\Seeders\EventRequirementQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EventPlanningFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_rates_replace_ai_amounts_and_remain_unchanged_in_scenarios(): void
    {
        $user = User::factory()->create();
        $vendor = DynamicVendor::create(['status' => 'active', 'vendor_json' => [
            'identity' => ['name' => 'Itemized Decor Studio', 'category' => 'Decorators'],
            'attributes' => [
                ['key' => 'price', 'label' => 'Price', 'type' => 'currency', 'value' => 90000],
                ['key' => 'mandap', 'label' => 'Mandap', 'type' => 'currency', 'value' => 20000],
                ['key' => 'chairs', 'label' => 'Chair decoration', 'type' => 'text', 'value' => 'Floral ties', 'pricing' => ['rate' => 25.5, 'unit' => 'per_guest']],
                ['key' => 'lighting', 'label' => 'Lighting', 'type' => 'text', 'value' => 'Warm lights', 'pricing' => ['rate' => 500, 'unit' => 'per_hour', 'quantity' => 4]],
            ],
        ]]);
        $this->mock(OpenRouterService::class)->shouldReceive('chat')->once()->andReturn(['choices' => [['message' => ['content' => json_encode([
            'title' => 'Vendor grounded plan', 'overview' => 'Selected decor.',
            'costing' => [['category' => 'Decor & Styling', 'amount' => 999999, 'vendor_ids' => [$vendor->id, 999999], 'attributes' => [['name' => 'Invented fee', 'cost' => 999999]]]],
        ])]]]]);
        $plan = app(EventPlanningService::class)->create($user, ['guest_count' => 100, 'answers' => []]);
        $this->assertSame(24550.0, (float) $plan->total_cost);
        $lines = $plan->summary['costing'][0]['attributes'];
        $this->assertSame(['Mandap', 'Chair decoration', 'Lighting'], array_column($lines, 'name'));
        $this->assertSame([20000, 2550, 2000], array_column($lines, 'cost'));
        $this->assertSame([$vendor->id], $plan->summary['costing'][0]['vendor_ids']);
        foreach ($plan->suggestions as $suggestion) {
            $this->assertEquals($plan->total_cost, $suggestion->total_cost);
            $this->assertSame($lines, $suggestion->summary['costing'][0]['attributes']);
        }
        $presentation = app(PlanPresentationService::class)->present($plan);
        $this->assertSame('Itemized Decor Studio', $presentation['costing'][0]['vendor_groups'][0]['name']);
        $this->assertEquals(24550, $presentation['costing'][0]['vendor_groups'][0]['amount']);
    }

    public function test_fallback_uses_saved_prices_and_excludes_insufficient_capacity(): void
    {
        $user = User::factory()->create();
        foreach ([['Small Hall', 50, 1000], ['Suitable Hall', 500, 123456]] as [$name, $capacity, $price]) {
            DynamicVendor::create(['status' => 'active', 'vendor_json' => [
                'identity' => ['name' => $name, 'category' => 'Venue'],
                'attributes' => [['key' => 'guest_capacity', 'value' => $capacity], ['key' => 'price', 'value' => $price]],
            ]]);
        }
        $this->mock(OpenRouterService::class)->shouldReceive('chat')->once()->andThrow(new \RuntimeException('Offline test'));
        $plan = app(EventPlanningService::class)->create($user, ['guest_count' => 100, 'answers' => ['wedding_budget' => 25]]);
        $this->assertEquals(123456, $plan->total_cost);
        $this->assertSame('Suitable Hall', $plan->summary['costing'][0]['attributes'][0]['vendor_name']);
        $this->assertSame('quote_required', $plan->summary['costing'][1]['pricing_status']);
        $this->assertCount(1, $plan->vendor_snapshot);
    }

    public function test_unknown_quantities_are_not_invented_and_duplicate_categories_are_not_billed_twice(): void
    {
        $vendor = ['id' => 20, 'name' => 'Lighting Vendor', 'category' => 'Decor', 'attributes' => [
            ['key' => 'light', 'label' => 'Lighting', 'pricing' => ['rate' => 100, 'unit' => 'per_hour']],
        ]];
        $summary = app(VendorCostingService::class)->ground(['costing' => [
            ['category' => 'Decor'], ['category' => 'Floral Decor'],
        ]], [$vendor], 200);
        $this->assertSame(0.0, $summary['total_cost']);
        $this->assertNull($summary['costing'][0]['attributes'][0]['quantity']);
        $this->assertSame('quote_required', $summary['costing'][0]['attributes'][0]['pricing_status']);
        $this->assertSame([], $summary['costing'][1]['attributes']);
    }

    public function test_saved_line_prices_are_not_rescaled_or_assigned_to_arbitrary_vendors(): void
    {
        $plan = $this->planFor(User::factory()->create());
        $summary = $plan->summary;
        $summary['costing'][0]['amount'] = 100;
        $plan->summary = $summary;
        $presentation = app(PlanPresentationService::class)->present($plan);
        $this->assertSame(750000.0, $presentation['costing'][0]['attributes'][0]['cost']);
        $this->assertSame('', $presentation['costing'][0]['attributes'][0]['vendor_name']);
        $this->assertNotNull($presentation['costing'][0]['cost_warning']);
    }

    public function test_landing_signin_has_user_and_vendor_dialog_choices(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('data-login-choice', false)
            ->assertSee('id="login-choice"', false)->assertSee('User Login')->assertSee('Vendor Login')
            ->assertSee('aria-labelledby="login-choice-title"', false);
    }

    public function test_ai_can_select_individual_attributes_without_double_billing_a_vendor(): void
    {
        $vendor = ['id' => 42, 'name' => 'One Decor Vendor', 'category' => 'Decor', 'attributes' => [
            ['key' => 'mandap', 'type' => 'currency', 'value' => 10000],
            ['key' => 'entrance', 'type' => 'currency', 'value' => 5000],
            ['key' => 'unused', 'type' => 'currency', 'value' => 3000],
        ]];
        $summary = app(VendorCostingService::class)->ground(['costing' => [
            ['category' => 'Ceremony setup', 'vendor_ids' => [42], 'attributes' => [['vendor_id' => 42, 'attribute_key' => 'mandap']]],
            ['category' => 'Entrance setup', 'vendor_ids' => [42], 'attributes' => [['vendor_id' => 42, 'attribute_key' => 'entrance'], ['vendor_id' => 42, 'attribute_key' => 'mandap']]],
        ]], [$vendor], 100);
        $this->assertSame(15000.0, $summary['total_cost']);
        $this->assertCount(1, $summary['costing'][1]['attributes']);
        $this->assertSame('entrance', $summary['costing'][1]['attributes'][0]['attribute_key']);
    }

    public function test_food_package_costs_use_saved_rates_not_submitted_prices(): void
    {
        $vendor = ['id' => 42, 'name' => 'Package Caterer', 'food_packages' => [
            ['id' => 'deluxe', 'name' => 'Deluxe menu', 'min_price_per_plate' => 800, 'max_price_per_plate' => 1000, 'items' => ['Live counter']],
        ], 'food_extras' => [
            ['id' => 'counter', 'name' => 'Live counter', 'min_price' => 100, 'max_price' => 150, 'unit' => 'per_plate'],
            ['id' => 'dessert', 'name' => 'Dessert counter', 'min_price' => 50, 'max_price' => 70, 'unit' => 'per_plate'],
        ]];
        $lines = app(VendorCostingService::class)->foodPackageLines([
            'selected_caterers' => [42], 'selected_food_package' => ['id' => 'deluxe', 'min_price_per_plate' => 1],
            'selected_food_extras' => ['counter', 'dessert'],
        ], [$vendor], 100);
        $this->assertEquals([80000, 5000], array_column($lines, 'cost'));
        $this->assertStringContainsString('starting rate', $lines[0]['value']);
        $this->assertSame('quote_required', app(VendorCostingService::class)->foodPackageLines(['selected_food_package' => ['id' => 'deluxe']], [$vendor], 100)[0]['pricing_status']);
    }

    public function test_pdf_includes_itemized_vendor_rates_across_multiple_pages(): void
    {
        $plan = $this->planFor(User::factory()->create());
        $presentation = app(PlanPresentationService::class)->present($plan);
        $presentation['costing'][0]['attributes'] = array_map(fn ($i) => [
            'name' => 'Service '.$i, 'vendor_name' => 'Saved Provider', 'value' => 'Confirmed database rate',
            'unit_price' => 25, 'quantity' => 200, 'unit' => 'per_guest', 'cost' => 5000,
        ], range(1, 30));
        $pdf = app(PlanPdfService::class)->render($plan, $presentation);
        $this->assertStringContainsString('Saved Provider', $pdf);
        $this->assertStringContainsString('Service 30', $pdf);
        $this->assertStringContainsString('x 200 guest', $pdf);
        $this->assertGreaterThan(1, preg_match_all('/\/Type \/Page\b/', $pdf));
    }

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

    public function test_catering_dropdown_and_menu_items_are_vendor_specific_and_multi_selectable(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        EventRequirementQuestion::where('question_code', 'food_type')->update([
            'options' => ['Paneer Tikka', 'Veg Biryani', 'Gulab Jamun'],
            'option_vendor_values' => ['paneer', 'biryani', 'jamun'],
            'option_metadata' => [
                'paneer' => ['category' => 'Starters', 'cost' => 125],
                'biryani' => ['category' => 'Main Course', 'cost' => 180],
                'jamun' => ['category' => 'Desserts', 'cost' => 50],
            ],
        ]);
        $first = DynamicVendor::create(['status' => 'active', 'vendor_json' => [
            'identity' => ['name' => 'First Caterer', 'category' => 'Catering'],
            'attributes' => [['label' => 'Menu Card Items', 'value' => 'Paneer Tikka, Veg Biryani']],
        ]]);
        $second = DynamicVendor::create(['status' => 'active', 'vendor_json' => [
            'identity' => ['name' => 'Second Caterer', 'category' => 'Catering'],
            'attributes' => [['label' => 'Menu Card Items', 'value' => 'Gulab Jamun']],
        ]]);
        DynamicVendor::create(['status' => 'active', 'vendor_json' => [
            'identity' => ['name' => 'Not A Caterer', 'category' => 'Venue'],
            'attributes' => [['label' => 'Menu Card Items', 'value' => 'Paneer Tikka']],
        ]]);

        $response = $this->get(route('ai-planner'))->assertOk();
        $vendors = collect($response->viewData('cateringVendors'));
        $this->assertSame(['First Caterer', 'Second Caterer'], $vendors->pluck('name')->all());
        $this->assertSame(['Paneer Tikka', 'Veg Biryani'], array_column($vendors->first()['menu_items'], 'title'));
        $this->assertSame(['Gulab Jamun'], array_column($vendors->last()['menu_items'], 'title'));
        $response->assertSee('selectedVendorIds:', false)
            ->assertSee('selectedCateringVendors()', false)
            ->assertSee('Available Catering Vendors');

        $this->post(route('ai-planner.generate'), [
            'category' => 'wedding',
            'guest_count' => 100,
            'answers' => [
                'wedding_budget' => 10,
                'food_type' => 'Paneer Tikka, Gulab Jamun',
                'selected_caterers' => [$first->id, $second->id],
                'food_menu_items' => [
                    ['id' => 'paneer', 'title' => 'Paneer Tikka', 'category' => 'Starters', 'cost' => 1, 'vendor_id' => $first->id, 'vendor_name' => 'spoofed'],
                    ['id' => 'jamun', 'title' => 'Gulab Jamun', 'category' => 'Desserts', 'cost' => 1, 'vendor_id' => $second->id, 'vendor_name' => 'spoofed'],
                ],
            ],
        ])->assertRedirect(route('user.login'))
            ->assertSessionHas('pending_event_plan.answers.food_menu_items.0.cost', 125.0)
            ->assertSessionHas('pending_event_plan.answers.food_menu_items.1.cost', 50.0)
            ->assertSessionHas('pending_event_plan.answers.food_menu_items.1.vendor_name', 'Second Caterer');
    }

    public function test_guest_package_generation_redirects_to_login_instead_of_restarting_questions(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);

        $this->post(route('ai-planner.generate'), [
            'category' => 'wedding',
            'guest_count' => 150,
            'answers' => [
                'wedding_budget' => 20,
                'food_type' => 'Deluxe Menu',
                'selected_food_package' => json_encode(['id' => 'deluxe_menu', 'name' => 'Deluxe Menu']),
            ],
        ])->assertRedirect(route('user.login'))
            ->assertSessionHas('pending_event_plan.answers.food_type', 'Deluxe Menu');
    }

    public function test_planner_disables_and_rejects_past_event_dates(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);

        $this->get(route('ai-planner'))
            ->assertOk()
            ->assertSee('isPastDate(day)', false)
            ->assertSee(':disabled="isPastDate(day)"', false)
            ->assertSee('canGoToPreviousMonth()', false);

        $this->from(route('ai-planner'))->post(route('ai-planner.generate'), [
            'category' => 'wedding',
            'guest_count' => 150,
            'answers' => [
                'wedding_budget' => 20,
                'food_type' => 'Deluxe Menu',
                'event_date' => now()->subDay()->toDateString(),
            ],
        ])->assertRedirect(route('ai-planner'))
            ->assertSessionHasErrors('answers.event_date')
            ->assertSessionMissing('pending_event_plan');
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
        $subscription = Subscription::create(['name' => 'Test Plan', 'price' => 0, 'interval' => 'free', 'features' => []]);
        $user = User::factory()->create(['status' => true, 'password' => 'password', 'subscription_id' => $subscription->id, 'subscription_ends_at' => now()->addMonth()]);
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
        $this->subscribe($other);
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
        $this->subscribe($user);
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
            ->assertSee('Caterer Menu Items')
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
        $this->subscribe($user);
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
