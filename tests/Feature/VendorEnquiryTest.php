<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\VendorEnquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VendorEnquiryTest extends TestCase
{
    use RefreshDatabase;
    public function test_vendor_enquiry_page_can_be_rendered(): void
    {
        $response = $this->get(route('vendor-enquiry.create'));
        $response->assertOk();
        $response->assertSee('Join As A');
        $response->assertSee('Business Information');
    }

    public function test_vendor_enquiry_form_can_be_submitted_successfully(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('banquet.jpg');

        $payload = [
            'business_name'   => 'Grand Shagun Palace',
            'contact_no'      => '+91 9876543210',
            'email'           => 'contact@grandshagun.com',
            'location'        => 'Bandra West, Mumbai',
            'vendor_category' => 'Banquet Hall / Venue',
            'service'         => 'Luxury banquet halls with in-house catering and royal mandap setup.',
            'capacity'        => '800 Guests',
            'price'           => '₹2,500/plate',
            'packages'        => 'Standard: ₹2L, Royal: ₹5L',
            'owner_name'      => 'Vikram Singhania',
            'owner_number'    => '+91 9876543210',
            'owner_email'     => 'vikram@grandshagun.com',
            'media'           => [$file],
        ];

        $response = $this->post(route('vendor-enquiry.store'), $payload);

        $response->assertRedirect(route('vendor-enquiry.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('vendor_enquiries', [
            'business_name' => 'Grand Shagun Palace',
            'email'         => 'contact@grandshagun.com',
            'owner_name'    => 'Vikram Singhania',
            'status'        => 'pending',
        ]);
    }

    public function test_admin_can_view_vendor_enquiries_list_and_details(): void
    {
        $admin = Admin::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@test.com',
            'phone'    => '1234567890',
            'password' => bcrypt('password'),
            'role'     => 'administrator',
        ]);

        $enquiry = VendorEnquiry::create([
            'business_name'   => 'Test Banquet',
            'contact_no'      => '1234567890',
            'email'           => 'test@banquet.com',
            'location'        => 'Mumbai',
            'vendor_category' => 'Banquet Hall / Venue',
            'service'         => 'Test service description',
            'owner_name'      => 'Test Owner',
            'owner_number'    => '1234567890',
            'owner_email'     => 'owner@banquet.com',
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.vendor-enquiries.index'));
        $response->assertOk();
        $response->assertSee('Test Banquet');

        $showResponse = $this->actingAs($admin, 'admin')->get(route('admin.vendor-enquiries.show', $enquiry));
        $showResponse->assertOk();
        $showResponse->assertSee('Test Banquet');
        $showResponse->assertSee('Test Owner');
    }
}
