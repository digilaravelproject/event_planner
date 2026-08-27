<?php

namespace Tests\Feature;

use App\Mail\AdminNewQueryMail;
use App\Mail\SubscriptionActivatedMail;
use App\Mail\SubscriptionExpiredMail;
use App\Mail\SubscriptionExpiryReminderMail;
use App\Mail\UserWelcomeMail;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Support\EmailRecipients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_sends_branded_welcome_email(): void
    {
        Mail::fake();

        $this->post(route('user.register.submit'), [
            'name' => 'New User', 'email' => 'new@example.com', 'mobile_number' => '9876543210',
            'password' => 'password', 'password_confirmation' => 'password',
        ])->assertRedirect(route('user.subscription'));

        Mail::assertSent(UserWelcomeMail::class, fn (UserWelcomeMail $mail) => $mail->hasTo('new@example.com'));
    }

    public function test_subscription_activation_and_expiry_send_one_email_each(): void
    {
        Mail::fake();
        $plan = Subscription::create(['name' => 'Free Plan', 'price' => 0, 'interval' => 'free', 'features' => []]);
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('user.subscribe.order'), ['plan_id' => $plan->id])->assertOk();
        Mail::assertSent(SubscriptionActivatedMail::class, fn (SubscriptionActivatedMail $mail) => $mail->hasTo($user->email));

        $record = UserSubscription::firstOrFail();
        $record->update(['ends_at' => now()->subMinute()]);
        $user->update(['subscription_ends_at' => now()->subMinute()]);

        $this->artisan('subscriptions:send-expiry-emails')->assertSuccessful();
        $this->assertSame('expired', $record->fresh()->status);
        Mail::assertSent(SubscriptionExpiredMail::class, 1);

        $this->artisan('subscriptions:send-expiry-emails')->assertSuccessful();
        Mail::assertSent(SubscriptionExpiredMail::class, 1);
    }

    public function test_subscription_expiry_reminder_is_sent_once_during_the_last_week(): void
    {
        Mail::fake();
        $plan = Subscription::create(['name' => 'Monthly Plan', 'price' => 999, 'interval' => 'monthly', 'features' => []]);
        $user = User::factory()->create([
            'subscription_id' => $plan->id,
            'subscription_ends_at' => now()->addDays(6),
        ]);
        $record = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $plan->id,
            'billing_cycle' => 'monthly',
            'amount' => 999,
            'status' => 'active',
            'starts_at' => now()->subDays(24),
            'ends_at' => now()->addDays(6),
            'paid_at' => now()->subDays(24),
        ]);

        $this->artisan('subscriptions:send-expiry-emails')->assertSuccessful();
        Mail::assertSent(SubscriptionExpiryReminderMail::class, 1);
        $this->assertNotNull($record->fresh()->reminder_sent_at);

        $this->artisan('subscriptions:send-expiry-emails')->assertSuccessful();
        Mail::assertSent(SubscriptionExpiryReminderMail::class, 1);
        Mail::assertNotSent(SubscriptionExpiredMail::class);
    }

    public function test_new_public_query_emails_the_admin_constant(): void
    {
        Mail::fake();

        $this->from(route('home'))->post(route('queries.store'), [
            'name' => 'Guest User', 'email' => 'guest@example.com', 'phone' => '9000000000',
            'subject' => 'Venue help', 'message' => 'Please suggest a venue.',
        ])->assertRedirect(route('home').'#query-form');

        Mail::assertSent(AdminNewQueryMail::class, fn (AdminNewQueryMail $mail) => $mail->hasTo(EmailRecipients::ADMIN));
    }
}
