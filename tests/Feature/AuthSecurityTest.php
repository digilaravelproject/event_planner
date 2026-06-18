<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test redirecting guest users and flashing the correct message.
     */
    public function test_guest_redirects_with_correct_flash_messages(): void
    {
        // 1. Admin guest request
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error', 'Kindly login first.');

        // 2. Vendor guest request
        $response = $this->get('/vendor/dashboard');
        $response->assertRedirect(route('vendor.login'));
        $response->assertSessionHas('error', 'Kindly login first.');

        // 3. User guest request
        $response = $this->get('/user/wizard');
        $response->assertRedirect(route('user.login'));
        $response->assertSessionHas('error', 'Kindly login first.');
    }

    /**
     * Test that logging out a vendor doesn't log out an admin in the same session, and vice versa.
     */
    public function test_guard_session_separation_on_logout(): void
    {
        // Create an admin
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create a vendor
        $vendor = Vendor::create([
            'name' => 'Vendor User',
            'email' => 'vendor@test.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'business_name' => 'Test Business',
            'category' => 'Test Category',
            'city' => 'Mumbai',
            'status' => true,
            'rating' => 5.0,
            'base_price' => 1000,
        ]);

        // Create a user
        $user = User::create([
            'name' => 'Standard User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'mobile_number' => '9876543210',
            'status' => true,
        ]);

        // Login all three in the test session
        $this->actingAs($admin, 'admin');
        $this->actingAs($vendor, 'vendor');
        $this->actingAs($user, 'web');

        // Verify all three are logged in
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('vendor')->check());
        $this->assertTrue(Auth::guard('web')->check());

        // Call vendor logout route via POST
        $response = $this->post(route('vendor.logout'));
        $response->assertRedirect(route('vendor.login'));

        // Verify vendor is logged out, but admin and user remain logged in
        $this->assertFalse(Auth::guard('vendor')->check());
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('web')->check());

        // Call admin logout route via POST
        $response = $this->post(route('admin.logout'));
        $response->assertRedirect(route('admin.login'));

        // Verify admin is logged out, but user remains logged in
        $this->assertFalse(Auth::guard('vendor')->check());
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('web')->check());

        // Call user logout route via POST
        $response = $this->post(route('user.logout'));
        $response->assertRedirect(route('user.login'));

        // Verify all are logged out
        $this->assertFalse(Auth::guard('vendor')->check());
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('web')->check());
    }
}
