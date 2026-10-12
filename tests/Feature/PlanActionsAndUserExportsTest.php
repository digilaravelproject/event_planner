<?php

namespace Tests\Feature;

use App\Mail\SharedPlanMail;
use App\Models\Admin;
use App\Models\AiSetting;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Models\UserSubscription;
use Database\Seeders\EventRequirementQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PlanActionsAndUserExportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reopen_and_update_the_same_plan(): void
    {
        $this->seed(EventRequirementQuestionSeeder::class);
        $user = User::factory()->create();
        $this->subscribe($user);
        $plan = $this->plan($user, ['wedding_budget' => 25, 'guest_capacity' => 200, 'wedding_tradition' => 'Maharashtrian Lagna']);
        AiSetting::setValue('openrouter_api_key', Crypt::encryptString('test-key'));
        Http::fake(['*/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => json_encode([
                'title' => 'Updated Wedding Plan',
                'overview' => 'Updated plan overview.',
                'costing' => [['category' => 'Venue', 'amount' => 3000000, 'percentage' => 100, 'summary' => 'Updated venue', 'vendor_ids' => [], 'attributes' => []]],
                'recommendations' => [],
                'notes' => [],
            ])]]],
        ])]);

        $this->actingAs($user)->get(route('user.plans.edit', $plan))
            ->assertOk()->assertSee('Update Plan')->assertSee('Maharashtrian Lagna');

        $this->actingAs($user)->put(route('user.plans.update', $plan), [
            'category' => 'wedding',
            'guest_count' => 350,
            'answers' => [
                'wedding_budget' => 30,
                'guest_capacity' => 350,
                'wedding_tradition' => 'Maharashtrian Lagna',
            ],
        ])->assertRedirect(route('user.plans.show', $plan));

        $plan->refresh();
        $this->assertSame(350, $plan->guest_count);
        $this->assertSame(30, $plan->answers['wedding_budget']);
        $this->assertSame('Updated Wedding Plan', $plan->title);
        $this->assertCount(0, $plan->suggestions);
        $this->assertSame(1, UserEventPlan::whereNull('parent_plan_id')->count());
    }

    public function test_user_can_email_the_plan_pdf(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->subscribe($user);
        $plan = $this->plan($user);

        $this->actingAs($user)->post(route('user.plans.share', $plan), ['email' => 'family@example.com'])
            ->assertRedirect()->assertSessionHas('success');

        Mail::assertSent(SharedPlanMail::class, fn (SharedPlanMail $mail): bool => $mail->hasTo('family@example.com'));
    }

    public function test_admin_can_export_all_users_as_pdf_and_excel(): void
    {
        $admin = Admin::create(['name' => 'Export Admin', 'email' => 'export@example.com', 'password' => 'password']);
        User::factory()->count(3)->create();

        $pdf = $this->actingAs($admin, 'admin')->get(route('admin.users.export.pdf'));
        $pdf->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $pdf->getContent());

        $excel = $this->actingAs($admin, 'admin')->get(route('admin.users.export.excel'));
        $excel->assertOk()->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringStartsWith('PK', $excel->getContent());
    }

    public function test_free_user_sees_disabled_edit_control_and_cannot_edit_plan(): void
    {
        $user = User::factory()->create();
        $free = Subscription::create(['name' => 'Free Plan', 'price' => 0, 'interval' => 'free', 'features' => []]);
        $user->update(['subscription_id' => $free->id, 'subscription_ends_at' => now()->addDays(30)]);
        $plan = $this->plan($user);

        $this->actingAs($user)->get(route('user.plans.show', $plan))
            ->assertOk()
            ->assertSee('Upgrade to a paid subscription to edit this plan.')
            ->assertSee('disabled', false);
        $this->actingAs($user)->get(route('user.plans.edit', $plan))->assertForbidden();
        $this->actingAs($user)->put(route('user.plans.update', $plan), [])->assertForbidden();
    }

    public function test_admin_can_view_filter_and_export_payment_transactions(): void
    {
        $admin = Admin::create(['name' => 'Payment Admin', 'email' => 'payments@example.com', 'password' => 'password']);
        $user = User::factory()->create(['name' => 'Payment Customer', 'email' => 'customer@example.com']);
        $plan = Subscription::create(['name' => '6 Monthly Plan', 'price' => 4999, 'interval' => 'six_months', 'features' => []]);
        $transaction = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $plan->id,
            'billing_cycle' => 'six_months',
            'amount' => 4999,
            'currency' => 'INR',
            'status' => 'active',
            'razorpay_order_id' => 'order_export_1',
            'razorpay_payment_id' => 'pay_export_1',
            'starts_at' => now(),
            'ends_at' => now()->addMonths(6),
            'paid_at' => now(),
        ]);

        $this->actingAs($admin, 'admin')->get(route('admin.transactions.index', ['search' => 'Payment Customer', 'status' => 'active']))
            ->assertOk()->assertSee('Payment Customer')->assertSee('pay_export_1')->assertSee('<table', false);

        $pdf = $this->actingAs($admin, 'admin')->get(route('admin.transactions.export.pdf'));
        $pdf->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $pdf->getContent());
        $this->actingAs($admin, 'admin')->get(route('admin.transactions.index'))->assertOk()->assertSee('fa-eye')->assertSee(route('admin.transactions.show', $transaction), false);
        $this->actingAs($admin, 'admin')->get(route('admin.transactions.show', $transaction))->assertOk()->assertSee('Transaction #'.$transaction->id);

        $excel = $this->actingAs($admin, 'admin')->get(route('admin.transactions.export.excel'));
        $excel->assertOk()->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringStartsWith('PK', $excel->getContent());
    }

    private function plan(User $user, array $answers = ['wedding_budget' => 25, 'guest_capacity' => 200]): UserEventPlan
    {
        return UserEventPlan::create([
            'user_id' => $user->id,
            'title' => 'Saved Wedding Plan',
            'category' => 'wedding',
            'guest_count' => 200,
            'answers' => $answers,
            'requirement_prompt' => 'prompt',
            'vendor_snapshot' => [],
            'summary' => ['title' => 'Saved Wedding Plan', 'overview' => 'Saved plan.', 'total_cost' => 2500000, 'costing' => []],
            'total_cost' => 2500000,
            'status' => 'completed',
        ]);
    }
}
