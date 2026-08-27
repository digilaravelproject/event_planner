<?php

namespace Tests\Feature;

use App\Models\EventRequirementQuestion;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Services\EventPlanningService;
use App\Services\OpenRouterService;
use App\Services\VendorAvailabilityService;
use Database\Seeders\EventRequirementQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlannerAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    private function answers(): array
    {
        return ['wedding_budget' => 20, 'service_area' => ['Pune'], 'event_date' => now()->addMonth()->toDateString(), 'event_time' => '18:00'];
    }

    private function vendor(string $name, array $schedule = [], string $category = 'Venue', int $rate = 10000): DynamicVendor
    {
        return DynamicVendor::create(['status' => 'active', 'vendor_json' => [
            'identity' => ['name' => $name, 'category' => $category],
            'availability' => array_replace(['available_dates' => [$this->answers()['event_date']], 'service_areas' => ['Pune'], 'start_time' => '09:00', 'end_time' => '23:00'], $schedule),
            'attributes' => [['key' => 'price', 'type' => 'currency', 'value' => $rate]],
        ]]);
    }

    private function plan(User $user, DynamicVendor $vendor, ?array $answers = null): UserEventPlan
    {
        return UserEventPlan::create(['user_id' => $user->id, 'title' => 'Saved celebration', 'category' => 'wedding', 'guest_count' => 100,
            'answers' => $answers ?? $this->answers(), 'vendor_snapshot' => [['id' => $vendor->id, 'name' => $vendor->name, 'category' => $vendor->category]],
            'summary' => ['title' => 'Saved celebration', 'overview' => 'Saved event plan.', 'costing' => [['category' => $vendor->category, 'amount' => 10000, 'vendor_ids' => [$vendor->id], 'attributes' => []]], 'total_cost' => 10000],
            'total_cost' => 10000, 'status' => 'completed', 'requirement_prompt' => 'Saved requirements']);
    }

    private function mockPlan(DynamicVendor $vendor): void
    {
        $this->mock(OpenRouterService::class)->shouldReceive('chat')->once()->andReturn(['choices' => [['message' => ['content' => json_encode([
            'title' => 'New celebration', 'overview' => 'New vendor choice', 'costing' => [['category' => $vendor->category, 'amount' => 999999, 'vendor_ids' => [$vendor->id]]],
        ])]]]]);
    }

    public function test_guest_custom_menu_null_string_hands_off_to_login_and_retains_answers(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        $answers = $this->answers() + ['food_menu_items' => '[]', 'selected_caterers' => '[]', 'selected_food_package' => 'null', 'selected_food_extras' => '[]'];
        $this->post(route('ai-planner.generate'), ['category' => 'wedding', 'guest_count' => 100, 'planner_step' => 6, 'answers' => $answers])
            ->assertSessionHasNoErrors()->assertRedirect(route('user.login'))
            ->assertSessionHas('pending_event_plan.answers.event_time', '18:00');
        $this->assertNull(session('pending_event_plan.answers.selected_food_package'));
        $this->get(route('user.login'))->assertOk()->assertSee('Your requirements are saved');
    }

    public function test_validation_failure_restores_answers_and_current_question_with_visible_errors(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        $answers = array_replace($this->answers(), ['event_time' => '28:00', 'selected_food_package' => 'null']);
        $this->from(route('ai-planner'))->post(route('ai-planner.generate'), ['category' => 'wedding', 'guest_count' => 432, 'planner_step' => 4, 'answers' => $answers])
            ->assertRedirect(route('ai-planner'))->assertSessionHasErrors('answers.event_time');
        $page = $this->get(route('ai-planner'))->assertOk()->assertSee('Your answers are still here')->assertSee('currentStep: 4', false);
        $this->assertEquals(432, $page->viewData('initialGuestCount'));
        $this->assertSame(['Pune'], $page->viewData('initialAnswers')['service_area']);
    }

    public function test_schedule_checks_date_area_capacity_status_and_overnight_hours(): void
    {
        $service = app(VendorAvailabilityService::class);
        $vendor = $this->vendor('Calendar venue');
        $this->assertSame('available', $service->assess($vendor, $this->answers(), 100)['status']);
        foreach ([['unavailable_dates' => [$this->answers()['event_date']]], ['available_dates' => [now()->addDays(90)->toDateString()]], ['service_areas' => ['Mumbai']], ['is_available' => '0'], ['start_time' => '20:00', 'end_time' => '23:00']] as $schedule) {
            $candidate = $this->vendor('Unavailable venue', $schedule);
            $this->assertSame('unavailable', $service->assess($candidate, $this->answers(), 100)['status']);
        }
        $vendor->status = 'inactive';
        $this->assertFalse($service->assess($vendor, $this->answers(), 100)['eligible']);
        $overnight = $this->vendor('Night venue', ['start_time' => '20:00', 'end_time' => '02:00']);
        $this->assertTrue($service->assess($overnight, array_replace($this->answers(), ['event_time' => '01:00']), 100)['eligible']);
        $doc = $vendor->vendor_json;
        $doc['attributes'][] = ['key' => 'guest_capacity', 'value' => 50];
        $vendor->vendor_json = $doc;
        $this->assertStringContainsString('Capacity is 50', implode(' ', $service->assess($vendor, $this->answers(), 100)['messages']));
        $unknown = $this->vendor('Unknown dates', ['available_dates' => []]);
        $this->assertSame('unconfirmed', $service->assess($unknown, $this->answers(), 100)['status']);
    }

    public function test_missing_provider_explains_conflicts_and_maps_question_location_labels(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        EventRequirementQuestion::where('question_code', 'service_area')->update(['options' => ['Pune city venues'], 'option_vendor_values' => ['Pune']]);
        $vendor = $this->vendor('Mapped venue');
        $answers = array_replace($this->answers(), ['service_area' => ['Pune city venues']]);
        $service = app(VendorAvailabilityService::class);
        $this->assertSame('available', $service->assess($vendor, $answers, 100)['status']);
        $user = $this->subscribe(User::factory()->create());
        $plan = $this->plan($user, $vendor, $answers);
        $summary = $plan->summary;
        $summary['costing'][0]['vendor_ids'] = [];
        $plan->update(['summary' => $summary]);
        $doc = $vendor->vendor_json;
        $doc['availability']['unavailable_dates'] = [$answers['event_date']];
        $vendor->update(['vendor_json' => $doc]);
        $this->actingAs($user)->get(route('user.plans.show', $plan))->assertOk()->assertSee('Mapped venue: Not available on '.$answers['event_date']);
    }

    public function test_summary_flags_a_menu_no_longer_offered_by_the_assigned_caterer(): void
    {
        $user = $this->subscribe(User::factory()->create());
        $vendor = $this->vendor('Changed menu caterer', [], 'Catering');
        $answers = $this->answers() + ['food_menu_items' => [['id' => 'paneer', 'title' => 'Paneer', 'vendor_id' => $vendor->id]]];
        $plan = $this->plan($user, $vendor, $answers);
        $this->actingAs($user)->get(route('user.plans.show', $plan))->assertOk()->assertSee('no longer offered by this vendor');
    }

    public function test_summary_rechecks_saved_vendor_and_excludes_unavailable_alternatives(): void
    {
        $user = $this->subscribe(User::factory()->create());
        $old = $this->vendor('Original venue');
        $plan = $this->plan($user, $old);
        $old->update(['status' => 'inactive']);
        $replacement = $this->vendor('Available replacement');
        $this->vendor('Blocked replacement', ['unavailable_dates' => [$this->answers()['event_date']]]);
        $this->vendor('Wrong area', ['service_areas' => ['Delhi']]);
        $response = $this->actingAs($user)->get(route('user.plans.show', $plan))->assertOk()
            ->assertSee('Vendor availability')->assertSee('This vendor is no longer active.')->assertSee('Available replacement')->assertDontSee('Blocked replacement')->assertDontSee('Wrong area');
        $this->assertSame([$replacement->id], array_column($response->viewData('availability')['vendor_'.$old->id]['alternatives'], 'id'));
        $this->assertEquals(10000, $plan->fresh()->total_cost);
    }

    public function test_replacement_overrides_ai_choice_reprices_and_preserves_original(): void
    {
        $user = $this->subscribe(User::factory()->create());
        $old = $this->vendor('Original venue');
        $new = $this->vendor('Chosen replacement', [], 'Venue', 27500);
        $plan = $this->plan($user, $old);
        $this->mockPlan($old);
        $response = $this->actingAs($user)->post(route('user.plans.regenerate', $plan), ['replacements' => ['vendor_'.$old->id => $new->id]])->assertSessionHasNoErrors();
        $created = UserEventPlan::whereNull('parent_plan_id')->where('id', '!=', $plan->id)->firstOrFail();
        $response->assertRedirect(route('user.plans.show', $created));
        $this->assertEquals(27500, $created->total_cost);
        $this->assertSame([$new->id], $created->summary['costing'][0]['vendor_ids']);
        $this->assertSame($plan->answers['event_date'], $created->answers['event_date']);
        $this->assertEquals(10000, $plan->fresh()->total_cost);
        $this->assertSame([$old->id], $plan->fresh()->summary['costing'][0]['vendor_ids']);
    }

    public function test_replacement_rejects_stale_schedule_wrong_service_and_non_owner(): void
    {
        $user = $this->subscribe(User::factory()->create());
        $old = $this->vendor('Original venue');
        $plan = $this->plan($user, $old);
        $blocked = $this->vendor('Just booked venue', ['unavailable_dates' => [$this->answers()['event_date']]]);
        $wrong = $this->vendor('Unrelated business', [], 'Photography');
        $this->mock(OpenRouterService::class)->shouldNotReceive('chat');
        foreach ([$blocked, $wrong] as $vendor) {
            $this->actingAs($user)->from(route('user.plans.show', $plan))->post(route('user.plans.regenerate', $plan), ['replacements' => ['vendor_'.$old->id => $vendor->id]])->assertSessionHasErrors('replacements');
        }
        $other = $this->subscribe(User::factory()->create());
        $this->actingAs($other)->post(route('user.plans.regenerate', $plan), ['replacements' => ['vendor_'.$old->id => $blocked->id]])->assertForbidden();
        $this->assertDatabaseCount('user_event_plans', 1);
    }

    public function test_unavailable_caterer_is_not_charged_and_replacement_uses_its_menu_rate(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        EventRequirementQuestion::where('question_code', 'food_type')->update(['options' => ['Paneer'], 'option_vendor_values' => ['paneer']]);
        $user = $this->subscribe(User::factory()->create());
        $old = $this->vendor('Booked caterer', ['unavailable_dates' => [$this->answers()['event_date']]], 'Catering');
        $new = $this->vendor('New caterer', [], 'Catering');
        $doc = $new->vendor_json;
        $doc['attributes'] = [['key' => 'menu_card_items', 'label' => 'Menu Card Items', 'value' => 'Paneer'], ['key' => 'paneer', 'label' => 'Paneer', 'pricing' => ['rate' => 350, 'unit' => 'per_guest']]];
        $new->update(['vendor_json' => $doc]);
        $answers = $this->answers() + ['selected_caterers' => [$old->id], 'food_menu_items' => [['id' => 'paneer', 'title' => 'Paneer', 'category' => 'Starter', 'cost' => 999, 'vendor_id' => $old->id, 'vendor_name' => $old->name]]];
        $this->mockPlan($old);
        $plan = app(EventPlanningService::class)->create($user, ['category' => 'wedding', 'guest_count' => 100, 'answers' => $answers]);
        $this->assertEquals(0, $plan->total_cost);
        $this->assertSame('quote_required', $plan->summary['costing'][0]['attributes'][0]['pricing_status']);
        $this->mockPlan($old);
        $this->actingAs($user)->post(route('user.plans.regenerate', $plan), ['replacements' => ['vendor_'.$old->id => $new->id]])->assertSessionHasNoErrors()->assertRedirect();
        $created = UserEventPlan::whereNull('parent_plan_id')->latest('id')->firstOrFail();
        $this->assertEquals(35000, $created->total_cost);
        $this->assertSame([$new->id], $created->summary['costing'][0]['vendor_ids']);
    }
}
