<?php

namespace Tests\Feature;

use App\Mail\StaffWelcomeMail;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_create_staff_and_credentials_are_emailed(): void
    {
        Mail::fake();
        $admin = Admin::create(['name' => 'Administrator', 'email' => 'owner@example.com', 'password' => 'password']);

        $this->actingAs($admin, 'admin')->post(route('admin.staff.store'), [
            'first_name' => 'Asha',
            'last_name' => 'Patil',
            'email' => 'asha@example.com',
            'phone' => '+91 9876543210',
            'permissions' => ['users', 'vendors'],
        ])->assertRedirect(route('admin.staff.index'))->assertSessionHasNoErrors();

        $staff = Admin::where('email', 'asha@example.com')->firstOrFail();
        $this->assertSame('staff', $staff->role);
        $this->assertSame(['users', 'vendors'], $staff->permissions);
        $this->assertTrue(Hash::check('ashapatil@123', $staff->password));
        Mail::assertSent(StaffWelcomeMail::class, fn ($mail) => $mail->hasTo('asha@example.com') && $mail->plainPassword === 'ashapatil@123');
    }

    public function test_staff_only_sees_and_accesses_assigned_sections(): void
    {
        $staff = Admin::create([
            'name' => 'Asha Patil', 'first_name' => 'Asha', 'last_name' => 'Patil', 'email' => 'asha@example.com',
            'phone' => '9876543210', 'password' => 'ashapatil@123', 'role' => 'staff', 'permissions' => ['users'], 'is_active' => true,
        ]);

        $this->actingAs($staff, 'admin')->get(route('admin.users.index'))->assertOk()->assertSee('Manage Users')->assertDontSee('Manage Vendors')->assertDontSee('Staff');
        $this->get('/admin')->assertRedirect(route('admin.users.index'));
        $this->get(route('admin.dynamic-vendors.index'))->assertForbidden();
        $this->get(route('admin.staff.index'))->assertForbidden();
    }

    public function test_inactive_staff_cannot_log_in(): void
    {
        Admin::create([
            'name' => 'Inactive Staff', 'email' => 'inactive@example.com', 'password' => 'staff@123',
            'role' => 'staff', 'permissions' => ['dashboard'], 'is_active' => false,
        ]);

        $this->post(route('admin.login.submit'), ['email' => 'inactive@example.com', 'password' => 'staff@123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }
}
