<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Services\EventPlanningService;
use App\Services\OpenRouterService;
use App\Services\PlanSuggestionService;
use App\Services\VendorCostingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanSuggestionTest extends TestCase
{
    use RefreshDatabase;

    private function vendor(string $name, int $price, array $extra = []): DynamicVendor
    {
        return DynamicVendor::create(['status' => 'active', 'vendor_json' => array_replace([
            'identity' => ['name' => $name, 'category' => 'Decor'],
            'attributes' => [['key' => 'mandap', 'label' => 'Mandap', 'type' => 'currency', 'value' => $price]],
        ], $extra)]);
    }

    private function snapshot(DynamicVendor $vendor): array
    {
        return ['id' => $vendor->id, 'name' => $vendor->name, 'category' => $vendor->category,
            'attribute_definitions' => $vendor->vendor_json['attributes'], 'food_packages' => $vendor->vendor_json['food_packages'] ?? [],
            'availability' => ['status' => 'unconfirmed', 'eligible' => true]];
    }

    private function plan(DynamicVendor $vendor): UserEventPlan
    {
        $user = $this->subscribe(User::factory()->create());
        $snapshot = $this->snapshot($vendor);
        $summary = app(VendorCostingService::class)->ground(['title' => 'Original plan', 'overview' => 'Original event', 'costing' => [['category' => $vendor->category, 'vendor_ids' => [$vendor->id]]]], [$snapshot], 100);

        return UserEventPlan::create(['user_id' => $user->id, 'title' => 'Original plan', 'category' => 'wedding', 'guest_count' => 100,
            'answers' => ['wedding_budget' => 20, 'event_date' => now()->addMonth()->toDateString()],
            'vendor_snapshot' => [$snapshot], 'summary' => $summary, 'total_cost' => $summary['total_cost'], 'status' => 'completed', 'requirement_prompt' => 'Saved requirements']);
    }

    public function test_suggestions_have_real_distinct_prices_and_name_the_vendor_change(): void
    {
        $original = $this->vendor('Original studio', 10000);
        $plan = $this->plan($original);
        $cheap = $this->vendor('Value studio', 7000);
        $high = $this->vendor('Another studio', 14000);
        $same = $this->vendor('Same cost', 10000);
        $duplicate = $this->vendor('Duplicate value', 7000);
        $this->mock(OpenRouterService::class)->shouldNotReceive('chat');
        app(EventPlanningService::class)->refreshSuggestions($plan);
        $suggestions = $plan->fresh()->suggestions;
        $this->assertEquals([7000, 14000], $suggestions->pluck('total_cost')->map('floatval')->all());
        foreach ($suggestions as $suggestion) {
            $line = $suggestion->summary['costing'][0]['attributes'][0];
            $this->assertEquals($line['unit_price'] * $line['quantity'], $suggestion->total_cost);
            $this->assertNotSame($original->id, $line['vendor_id']);
            $this->assertSame($plan->guest_count, $suggestion->guest_count);
            $this->assertArrayNotHasKey('target_budget', $suggestion->summary);
            $this->assertEquals((float) $suggestion->total_cost - 10000, $suggestion->summary['comparison']['difference']);
            $this->assertStringContainsString('Replace Original studio', $suggestion->summary['overview']);
            $this->assertSame($suggestion->summary['costing'][0]['vendor_ids'], $suggestion->answers['preferred_vendor_ids']);
        }
        $this->assertEquals(10000, $plan->fresh()->total_cost);
        $this->actingAs($plan->user)->get(route('user.plans.show', $plan))->assertOk()->assertSee('₹7,000')->assertSee('₹14,000')->assertDontSee('lower budget target');
    }

    public function test_unavailable_missing_scope_and_changed_quantity_are_not_cheaper_suggestions(): void
    {
        $original = $this->vendor('Original', 10000);
        $plan = $this->plan($original);
        $this->vendor('Unavailable', 1000, ['availability' => ['is_available' => false]]);
        $this->vendor('Booked', 2000, ['availability' => ['unavailable_dates' => [$plan->answers['event_date']]]]);
        $this->vendor('Unpriced', 0, ['attributes' => []]);
        $this->vendor('Different service', 0, ['attributes' => [['key' => 'entrance', 'label' => 'Entrance', 'type' => 'currency', 'value' => 500]]]);
        $this->vendor('Hourly instead', 0, ['attributes' => [['key' => 'mandap', 'label' => 'Mandap', 'pricing' => ['rate' => 20, 'unit' => 'per_hour', 'quantity' => 2]]]]);
        app(EventPlanningService::class)->refreshSuggestions($plan);
        $this->assertCount(0, $plan->fresh()->suggestions);
        $this->actingAs($plan->user)->get(route('user.plans.show', $plan))->assertOk()->assertSee('No comparable alternatives');
    }

    public function test_refresh_replaces_legacy_clones_and_preserves_original_and_enforces_ownership(): void
    {
        $original = $this->vendor('Original', 10000);
        $plan = $this->plan($original);
        $legacy = $plan->replicate();
        $legacy->parent_plan_id = $plan->id;
        $legacy->title = 'Essential Wedding Plan';
        $summary = $legacy->summary;
        $summary['target_budget'] = 7500;
        $legacy->summary = $summary;
        $legacy->save();
        $this->vendor('Real alternative', 8500);
        $other = $this->subscribe(User::factory()->create());
        $this->actingAs($other)->post(route('user.plans.suggestions.refresh', $plan))->assertForbidden();
        $this->actingAs($plan->user)->get(route('user.plans.show', $plan))->assertOk()->assertDontSee('Essential Wedding Plan')->assertSee('Refresh priced alternatives');
        $this->post(route('user.plans.suggestions.refresh', $plan))->assertRedirect(route('user.plans.show', $plan))->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('user_event_plans', ['id' => $legacy->id]);
        $this->assertCount(1, $plan->fresh()->suggestions);
        $this->assertEquals(8500, $plan->fresh()->suggestions->first()->total_cost);
        $this->assertEquals(10000, $plan->fresh()->total_cost);
    }

    public function test_new_generation_automatically_creates_priced_alternatives(): void
    {
        $original = $this->vendor('Original', 10000);
        $this->vendor('Alternative', 8000);
        $user = User::factory()->create();
        $this->mock(OpenRouterService::class)->shouldReceive('chat')->once()->andReturn(['choices' => [['message' => ['content' => json_encode([
            'title' => 'New plan', 'overview' => 'Event', 'costing' => [['category' => 'Decor', 'vendor_ids' => [$original->id]]],
        ])]]]]);
        $plan = app(EventPlanningService::class)->create($user, ['guest_count' => 100, 'answers' => ['wedding_budget' => 20]]);
        $this->assertEquals(10000, $plan->total_cost);
        $this->assertCount(1, $plan->suggestions);
        $this->assertEquals(8000, $plan->suggestions->first()->total_cost);
    }

    public function test_catering_suggestion_keeps_dishes_and_uses_new_vendor_per_guest_price(): void
    {
        $old = $this->vendor('Old caterer', 0, ['identity' => ['name' => 'Old caterer', 'category' => 'Catering'], 'attributes' => [
            ['key' => 'menu_card_items', 'label' => 'Menu Card Items', 'value' => 'Paneer'],
            ['key' => 'paneer', 'label' => 'Paneer', 'pricing' => ['rate' => 200, 'unit' => 'per_guest']],
        ]]);
        $new = $this->vendor('New caterer', 0, ['identity' => ['name' => 'New caterer', 'category' => 'Catering'], 'attributes' => [
            ['key' => 'menu_card_items', 'label' => 'Menu Card Items', 'value' => 'Paneer'],
            ['key' => 'paneer', 'label' => 'Paneer', 'pricing' => ['rate' => 150, 'unit' => 'per_guest']],
        ]]);
        $plan = $this->plan($old);
        $plan->answers = $plan->answers + ['selected_caterers' => [$old->id], 'food_menu_items' => [['id' => 'paneer', 'title' => 'Paneer', 'vendor_id' => $old->id, 'vendor_name' => $old->name, 'cost' => 200]]];
        $plan->save();
        $results = app(PlanSuggestionService::class)->alternatives($plan, [$this->snapshot($old), $this->snapshot($new)]);
        $this->assertCount(1, $results);
        $this->assertEquals(15000, $results[0]['summary']['total_cost']);
        $this->assertSame([$new->id], $results[0]['answers']['selected_caterers']);
        $this->assertEquals(150, $results[0]['answers']['food_menu_items'][0]['cost']);
    }
}
