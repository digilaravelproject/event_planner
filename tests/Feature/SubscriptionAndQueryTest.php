<?php

namespace Tests\Feature;

use App\Mail\UserQueryReplyMail;
use App\Models\Admin;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SubscriptionAndQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_user_is_sent_to_plans_and_can_activate_free_plan(): void
    {
        $plan = Subscription::create(['name' => 'Free Plan', 'price' => 0, 'interval' => 'free', 'features' => ['Planner access']]);
        $user = User::factory()->create(['status' => true, 'password' => 'password', 'subscription_id' => $plan->id, 'subscription_ends_at' => now()->subDay()]);

        $this->post(route('user.login.submit'), ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('user.subscription'));
        $this->actingAs($user)->postJson(route('user.subscribe.order'), ['plan_id' => $plan->id])->assertOk()->assertJsonPath('free', true);

        $this->assertTrue($user->fresh()->hasActiveSubscription());
        $this->assertTrue($user->fresh()->subscription_ends_at->between(now()->addDays(29), now()->addDays(31)));
        $this->assertDatabaseHas('user_subscriptions', ['user_id' => $user->id, 'subscription_id' => $plan->id, 'status' => 'active', 'amount' => 0]);
    }

    public function test_paid_plan_uses_server_order_and_verified_signature(): void
    {
        config(['services.razorpay.key_id' => 'rzp_test_key', 'services.razorpay.key_secret' => 'secret', 'services.razorpay.url' => 'https://api.razorpay.test/v1']);
        Http::fake(['https://api.razorpay.test/v1/orders' => Http::response(['id' => 'order_123', 'amount' => 99900, 'currency' => 'INR'])]);
        $plan = Subscription::create(['name' => 'Premium Plan', 'price' => 999, 'interval' => 'three_months', 'features' => []]);
        $user = User::factory()->create(['status' => true]);

        $this->actingAs($user)->postJson(route('user.subscribe.order'), ['plan_id' => $plan->id])->assertOk()->assertJsonPath('order_id', 'order_123');
        $paymentId = 'pay_123';
        $signature = hash_hmac('sha256', 'order_123|'.$paymentId, 'secret');
        $this->actingAs($user)->postJson(route('user.subscribe.verify'), ['razorpay_order_id' => 'order_123', 'razorpay_payment_id' => $paymentId, 'razorpay_signature' => $signature])->assertOk();

        $this->assertDatabaseHas('user_subscriptions', ['razorpay_order_id' => 'order_123', 'razorpay_payment_id' => $paymentId, 'status' => 'active']);
        $this->assertSame($plan->id, $user->fresh()->subscription_id);
        $this->assertTrue($user->fresh()->subscription_ends_at->between(now()->addMonths(3)->subDay(), now()->addMonths(3)->addDay()));
    }

    public function test_query_can_be_submitted_replied_to_and_seen_in_user_panel(): void
    {
        Mail::fake();
        $user = User::factory()->create(['status' => true, 'mobile_number' => '9876543210']);
        $this->subscribe($user);
        $admin = Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password']);
        $this->actingAs($user)->from(route('user.queries.index'))->post(route('user.queries.store'), ['phone' => '9876543210', 'subject' => 'Need venue help', 'message' => 'Please suggest a venue.'])->assertRedirect(route('user.queries.index').'#query-form')->assertSessionHas('success');
        $query = UserQuery::firstOrFail();

        $this->actingAs($admin, 'admin')->get(route('admin.user-queries.index'))->assertOk()->assertSee('<table', false)->assertSee('Need venue help');
        $this->actingAs($admin, 'admin')->post(route('admin.user-queries.reply', $query), ['admin_reply' => 'We will share three options today.'])->assertSessionHas('success');
        Mail::assertSent(UserQueryReplyMail::class, fn ($mail) => $mail->hasTo($user->email));
        $this->actingAs($user)->get(route('user.queries.index'))->assertOk()->assertSee('We will share three options today.')->assertSee('<table', false);
    }

    public function test_unsubscribed_user_can_only_open_subscription_and_profile_pages(): void
    {
        $user = User::factory()->create(['status' => true, 'mobile_number' => '9000000000']);

        foreach (['user.dashboard', 'user.plans.index', 'user.queries.index', 'user.notifications.index'] as $route) {
            $this->actingAs($user)->get(route($route))->assertRedirect(route('user.subscription'));
        }
        $this->actingAs($user)->get(route('user.subscription'))->assertOk()->assertDontSee(route('user.dashboard'), false)->assertDontSee(route('user.queries.index'), false);
        $this->actingAs($user)->get(route('user.profile'))->assertOk()->assertSee('Manage Profile');
        $this->actingAs($user)->put(route('user.profile.update'), ['name' => 'Updated User', 'email' => $user->email, 'mobile_number' => '9111111111'])->assertRedirect(route('user.profile'));
        $this->assertSame('Updated User', $user->fresh()->name);
    }

    public function test_public_query_redirects_back_to_the_query_form(): void
    {
        $this->from(route('home'))->post(route('queries.store'), ['name' => 'Guest', 'email' => 'guest@example.com', 'phone' => '9000000000', 'subject' => 'Guest query', 'message' => 'Please contact me.'])->assertRedirect(route('home').'#query-form');
    }

    public function test_subscription_flags_distinguish_free_expired_and_subscribed_users(): void
    {
        $free = User::factory()->create();
        $expiredPlan = Subscription::create(['name' => 'Expired Plan', 'price' => 500, 'interval' => 'three_months', 'features' => []]);
        $expired = User::factory()->create(['subscription_id' => $expiredPlan->id, 'subscription_ends_at' => now()->subDay()]);
        $subscribed = User::factory()->create();
        $this->subscribe($subscribed);

        $this->assertSame('free', $free->subscriptionFlag());
        $this->assertSame('expired', $expired->subscriptionFlag());
        $this->assertSame('subscribed', $subscribed->subscriptionFlag());
        $this->assertTrue(Route::has('admin.user-queries.index'));
    }
}
