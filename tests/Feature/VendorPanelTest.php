<?php

namespace Tests\Feature;

use App\Mail\VendorWelcomeMail;
use App\Models\VendorAccount;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class VendorPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_register_receive_welcome_email_and_open_dashboard(): void
    {
        Mail::fake();

        $this->post(route('vendor.register.submit'), [
            'name' => 'Asha Patil',
            'business_name' => 'Asha Celebrations',
            'email' => 'asha@example.com',
            'phone' => '9876543210',
            'category' => 'Decorator',
            'city' => 'Pune',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('vendor.dashboard'));

        $account = VendorAccount::firstOrFail();
        $this->assertAuthenticatedAs($account, 'vendor');
        Mail::assertSent(VendorWelcomeMail::class, fn (VendorWelcomeMail $mail) => $mail->vendor->is($account));
        $this->get(route('vendor.dashboard'))->assertOk()->assertSee('Asha Celebrations');
    }

    public function test_vendor_can_manage_only_their_own_dynamic_business_details(): void
    {
        $owner = VendorAccount::create($this->accountData('owner@example.com', 'Owner Events'));
        $other = VendorAccount::create($this->accountData('other@example.com', 'Other Events'));

        $this->actingAs($owner, 'vendor')->post(route('vendor.vendors.store'), $this->vendorPayload())
            ->assertRedirect();

        $listing = DynamicVendor::firstOrFail();
        $this->assertSame($owner->id, $listing->vendor_account_id);
        $this->get(route('vendor.vendors.edit', $listing))->assertOk()->assertSee('Dynamic Attributes');

        $updated = $this->vendorPayload();
        $updated['name'] = 'Owner Events Premium';
        $this->put(route('vendor.vendors.update', $listing), $updated)->assertRedirect();
        $this->assertSame('Owner Events Premium', $listing->refresh()->name);

        auth('vendor')->logout();
        $this->actingAs($other, 'vendor');
        $this->get(route('vendor.vendors.show', $listing))->assertNotFound();
        $this->put(route('vendor.vendors.update', $listing), $updated)->assertNotFound();
        $this->delete(route('vendor.vendors.destroy', $listing))->assertNotFound();
        $this->assertDatabaseHas('vendors_dynamic', ['id' => $listing->id]);
    }

    private function accountData(string $email, string $business): array
    {
        return ['name' => $business, 'business_name' => $business, 'email' => $email, 'phone' => '9876543210', 'password' => 'password123'];
    }

    private function vendorPayload(): array
    {
        return [
            'name' => 'Owner Events',
            'category' => 'Decorator',
            'status' => 'draft',
            'attributes' => [[
                'label' => 'Starting Price', 'type' => 'currency', 'value' => '50000', 'min_value' => '0',
            ]],
            'short_description' => 'Premium event decoration.',
        ];
    }
}
