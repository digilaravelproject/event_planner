<?php

namespace Tests\Feature;

use App\Mail\SharedPlanMail;
use App\Models\Admin;
use App\Models\AiSetting;
use App\Models\User;
use App\Models\UserEventPlan;
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
        $this->assertCount(6, $plan->suggestions);
        $this->assertSame(1, UserEventPlan::whereNull('parent_plan_id')->count());
    }

    public function test_user_can_email_the_plan_pdf(): void
    {
        Mail::fake();
        $user = User::factory()->create();
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
