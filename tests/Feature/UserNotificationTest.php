<?php

namespace Tests\Feature;

use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_only_assigned_delivered_notifications_and_can_mark_one_read(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $visible = AdminNotification::create(['title' => 'Menu update', 'message' => 'New menu prices are available.', 'notification_type' => 'information', 'status' => 'sent', 'sent_at' => now()]);
        $draft = AdminNotification::create(['title' => 'Draft update', 'message' => 'Not delivered.', 'notification_type' => 'information', 'status' => 'draft']);
        $other = AdminNotification::create(['title' => 'Other user update', 'message' => 'Private.', 'notification_type' => 'information', 'status' => 'sent', 'sent_at' => now()]);
        $visible->users()->attach($user->id);
        $draft->users()->attach($user->id);
        $other->users()->attach($otherUser->id);

        $this->actingAs($user)->get(route('user.notifications.index'))
            ->assertOk()->assertSee('Menu update')->assertSee('Unread')->assertDontSee('Draft update')->assertDontSee('Other user update');

        $this->actingAs($user)->patch(route('user.notifications.read', $visible))->assertRedirect();
        $this->assertDatabaseHas('notification_users', ['notification_id' => $visible->id, 'user_id' => $user->id, 'is_read' => true]);
    }

    public function test_user_can_mark_all_delivered_notifications_read(): void
    {
        $user = User::factory()->create();
        $notifications = collect(range(1, 2))->map(fn (int $number) => AdminNotification::create(['title' => "Update {$number}", 'message' => 'Message', 'notification_type' => 'success', 'status' => 'sent', 'sent_at' => now()]));
        $notifications->each(fn (AdminNotification $notification) => $notification->users()->attach($user->id));

        $this->actingAs($user)->patch(route('user.notifications.read-all'))->assertRedirect();

        $this->assertDatabaseMissing('notification_users', ['user_id' => $user->id, 'is_read' => false]);
    }
}
