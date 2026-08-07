<?php

namespace Tests\Feature;

use App\Models\AiSetting;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Models\Admin;
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

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Grand Wedding', false)
            ->assertSeeText('50 – 150 Guests');

        $this->get(route('ai-planner', ['type' => 'wedding', 'guests' => 300]))
            ->assertOk()
            ->assertSee('What is your total estimated wedding budget?')
            ->assertSee('How many guests will celebrate with you?');
    }

    public function test_guest_requirements_resume_after_login_and_create_history_with_suggestions(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
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
            ],
        ];

        $this->post(route('ai-planner.generate'), $payload)
            ->assertRedirect(route('user.login'))
            ->assertSessionHas('pending_event_plan');

        $this->post(route('user.login.submit'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('ai-planner.resume'));

        $response = $this->actingAs($user)->get(route('ai-planner.resume'));
        $plan = UserEventPlan::whereNull('parent_plan_id')->firstOrFail();
        $response->assertRedirect(route('user.plans.show', $plan));
        $this->assertCount(2, $plan->suggestions);

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

    public function test_user_dashboard_and_pdf_download_are_available(): void
    {
        $user = User::factory()->create();
        $plan = $this->planFor($user);

        $this->actingAs($user)->get(route('user.dashboard'))
            ->assertOk()->assertSee('planning studio')->assertSee('Detailed plan', false);

        $response = $this->actingAs($user)->get(route('user.plans.download', $plan));
        $response->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $response->getContent());
        $this->assertStringContainsString('DETAILED COSTING', $response->getContent());
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
        $this->assertStringContainsString('AI REQUIREMENT PROMPT', $response->getContent());
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
                'costing' => [['category' => 'Catering', 'amount' => 750000, 'percentage' => 30, 'summary' => 'Food and service', 'vendor_ids' => [10]]],
                'recommendations' => [['vendor_id' => 10, 'name' => 'Royal Caterer', 'category' => 'Catering', 'reason' => 'Matches the menu.', 'estimated_cost' => 750000]],
                'notes' => ['Confirm availability.'],
            ],
            'total_cost' => 2500000,
            'model' => 'openrouter/auto',
            'status' => 'completed',
        ]);
    }
}
