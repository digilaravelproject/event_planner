<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Venue;
use App\Models\Admin;
use App\Models\EventPlan;
use App\Models\QuoteRequest;
use App\Models\MasterRegistry;
use App\Models\SystemMaster;
use App\Models\VendorRegistry;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class QuoteRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Default registries are inserted by migrations.
        // We only register the custom dynamic registry "test".
        MasterRegistry::firstOrCreate(
            ['key' => 'test'],
            ['title' => 'Test']
        );

        // Add options for each registry using firstOrCreate
        SystemMaster::firstOrCreate(['type' => 'event_types', 'label' => 'Wedding']);
        SystemMaster::firstOrCreate(['type' => 'budget_ranges', 'label' => 'Under ₹5 Lakhs']);
        SystemMaster::firstOrCreate(['type' => 'guest_ranges', 'label' => '100 - 250 guests']);
        SystemMaster::firstOrCreate(['type' => 'venue_types', 'label' => 'Banquet Hall']);
        SystemMaster::firstOrCreate(['type' => 'food_types', 'label' => 'Veg Only']);
        SystemMaster::firstOrCreate(['type' => 'styles', 'label' => 'Luxury']);
        SystemMaster::firstOrCreate(['type' => 'decoration_types', 'label' => 'Floral']);
        SystemMaster::firstOrCreate(['type' => 'entertainment_types', 'label' => 'Live Band']);
        SystemMaster::firstOrCreate(['type' => 'test', 'label' => 'Test Subtool']); // Dynamic item

        // Create subscription to satisfy foreign key constraints
        Subscription::create([
            'id' => 1,
            'name' => 'Premium Plan',
            'price' => 999.00,
            'interval' => 'yearly',
            'features' => ['all'],
        ]);
    }

    /**
     * Test dynamic registries display and save selections.
     */
    public function test_wizard_saves_dynamic_selections(): void
    {
        $user = User::create([
            'name' => 'Wizard User',
            'email' => 'wizard@test.com',
            'password' => Hash::make('password'),
            'mobile_number' => '1234567890',
            'status' => true,
            'subscription_id' => 1,
            'subscription_ends_at' => now()->addYear(),
        ]);

        $this->actingAs($user, 'web');

        $payload = [
            'event_type' => 'Wedding',
            'budget' => 'Under ₹5 Lakhs',
            'guests' => '100 - 250 guests',
            'location' => 'Bandra, Mumbai',
            'date' => '2026-07-01',
            'venue_type' => 'Banquet Hall',
            'food_type' => 'Veg Only',
            'style' => 'Luxury',
            'decoration_type' => 'Floral',
            'entertainment_type' => 'Live Band',
            'test' => 'Test Subtool', // Dynamic registry selection
        ];

        $response = $this->post(route('user.wizard.generate'), $payload);

        $response->assertJson(['success' => true]);

        $plan = EventPlan::first();
        $this->assertNotNull($plan);
        $this->assertEquals('Test Subtool', $plan->dynamic_selections['test']);
    }

    /**
     * Test vendor costing matching, suggestions sorting, and detail modal.
     */
    public function test_vendor_costing_matching_and_quote_requests(): void
    {
        $user = User::create([
            'name' => 'Wizard User',
            'email' => 'wizard@test.com',
            'password' => Hash::make('password'),
            'mobile_number' => '1234567890',
            'status' => true,
            'subscription_id' => 1,
            'subscription_ends_at' => now()->addYear(),
        ]);

        // Create a vendor in the same location (Mumbai)
        $vendor = Vendor::create([
            'name' => 'Premium Catering & Events',
            'email' => 'catering@premium.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'business_name' => 'Premium Events',
            'category' => 'Catering',
            'city' => 'Mumbai',
            'status' => true,
            'rating' => 4.9,
            'base_price' => 200000.00,
        ]);

        // Link a venue to get capacity
        Venue::create([
            'vendor_id' => $vendor->id,
            'name' => 'Premium Palace Banquet',
            'city' => 'Mumbai',
            'capacity' => 300,
            'price_per_day' => 200000.00,
            'status' => true,
        ]);

        $eventType = SystemMaster::where('type', 'event_types')->where('label', 'Wedding')->first();

        // Configure vendor registry prices
        VendorRegistry::create([
            'vendor_id' => $vendor->id,
            'event_type_id' => $eventType->id,
            'registry_key' => 'food_types',
            'item_label' => 'Veg Only',
            'share_percentage' => 30.00,
            'share_rupees' => 60000.00,
            'status' => true,
        ]);

        VendorRegistry::create([
            'vendor_id' => $vendor->id,
            'event_type_id' => $eventType->id,
            'registry_key' => 'test',
            'item_label' => 'Test Subtool',
            'share_percentage' => 10.00,
            'share_rupees' => 20000.00,
            'status' => true,
        ]);

        // Create user event plan
        $plan = EventPlan::create([
            'user_id' => $user->id,
            'event_type' => 'Wedding',
            'budget' => 'Under ₹5 Lakhs',
            'guests' => '100 - 250 guests',
            'location' => 'Mumbai',
            'date' => '2026-07-01',
            'venue_type' => 'Banquet Hall',
            'food_type' => 'Veg Only',
            'style' => 'Luxury',
            'decoration_type' => 'Floral',
            'entertainment_type' => 'Live Band',
            'dynamic_selections' => ['test' => 'Test Subtool'],
            'budget_shares' => [],
        ]);

        $this->actingAs($user, 'web');

        // Verify summary page displays matched vendor pricing
        $response = $this->get(route('user.summary', $plan->id));
        $response->assertStatus(200);
        $response->assertSee('Premium Events');
        $response->assertSee('80,000'); // 60,000 food + 20,000 test

        // Send Quote Request from user to vendor
        $response = $this->post(route('user.quote-requests.store'), [
            'vendor_id' => $vendor->id,
            'event_plan_id' => $plan->id,
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('quote_requests', [
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'event_plan_id' => $plan->id,
        ]);

        // Verify Vendor can view Quote Requests in Vendor Panel
        $this->actingAs($vendor, 'vendor');

        $response = $this->get(route('vendor.quote-requests.index'));
        $response->assertStatus(200);
        $response->assertSee('Wizard User');
        $response->assertSee('wizard@test.com');
        $response->assertSee('80,000');

        // Delete Quote Request in Vendor Panel
        $quoteRequest = QuoteRequest::first();
        $response = $this->delete(route('vendor.quote-requests.destroy', $quoteRequest->id));
        $response->assertRedirect(route('vendor.quote-requests.index'));
        $this->assertDatabaseMissing('quote_requests', ['id' => $quoteRequest->id]);
    }
}
