<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\EventRequirementQuestion;
use App\Models\AiSetting;
use App\Models\LandingContent;
use App\Models\Page;
use App\Models\User;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Database\Seeders\AdminModulesSeeder;
use Database\Seeders\EventRequirementQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use App\Services\OpenRouterService;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AdminModulesSeeder::class);
        $admin = Admin::create(['name' => 'Module Admin', 'email' => 'modules@example.com', 'password' => 'password']);
        $this->actingAs($admin, 'admin');
    }

    public function test_all_new_admin_module_pages_render(): void
    {
        foreach (['admin.vendor-analytics.index', 'admin.ai.manage', 'admin.event-questions.index', 'admin.notifications.index', 'admin.pages.index', 'admin.feedback.index'] as $route) {
            $this->get(route($route))->assertOk();
        }

        foreach (array_keys(LandingContent::TYPES) as $type) {
            $this->get(route('admin.landing-content.index', $type))->assertOk();
        }
    }

    public function test_admin_can_manage_database_backed_landing_content(): void
    {
        $this->post(route('admin.landing-content.store', 'how-it-works'), [
            'title' => 'Share event details',
            'body' => 'Tell us your budget and guest count.',
            'eyebrow' => 'Two minutes',
            'footer' => 'Guided Input',
            'display_order' => 1,
            'status' => 1,
        ])->assertRedirect(route('admin.landing-content.index', 'how-it-works'));

        $item = LandingContent::firstOrFail();
        $this->get(route('home'))->assertOk()->assertSee('Share event details')->assertSee('Tell us your budget and guest count.');
        $this->put(route('admin.landing-content.update', ['how-it-works', $item]), [
            'title' => 'Share your celebration details',
            'body' => 'Updated landing content.',
            'display_order' => 2,
            'status' => 1,
        ])->assertRedirect();
        $this->assertDatabaseHas('landing_contents', ['id' => $item->id, 'title' => 'Share your celebration details']);
        $this->delete(route('admin.landing-content.destroy', ['how-it-works', $item]))->assertRedirect();
        $this->assertDatabaseMissing('landing_contents', ['id' => $item->id]);
    }

    public function test_openrouter_configuration_and_chat_client_use_the_selected_model_and_prompt(): void
    {
        $this->get(route('admin.ai.manage'))->assertOk()->assertSee('OpenRouter Configuration')->assertDontSee('OpenAI API Key');

        AiSetting::setValue('openrouter_api_key', Crypt::encryptString('test-key'));
        AiSetting::setValue('openrouter_model', 'openrouter/auto');
        AiSetting::setValue('openrouter_prompt_template', 'Use only supplied vendor data.');
        Http::fake(['openrouter.ai/api/v1/chat/completions' => Http::response(['choices' => [['message' => ['content' => 'ok']]]])]);

        app(OpenRouterService::class)->chat([['role' => 'user', 'content' => 'Plan my event.']]);

        Http::assertSent(fn ($request) => $request->url() === 'https://openrouter.ai/api/v1/chat/completions'
            && $request['model'] === 'openrouter/auto'
            && data_get($request->data(), 'messages.0.content') === 'Use only supplied vendor data.');
    }

    public function test_question_resource_validates_and_persists(): void
    {
        $this->post(route('admin.event-questions.store'), [
            'question' => 'Which colours?', 'question_code' => 'colours', 'question_type' => 'checkbox',
            'options_text' => "Red\nBlue", 'display_order' => 99, 'is_required' => 1, 'status' => 1,
        ])->assertRedirect(route('admin.event-questions.index'));
        $this->assertDatabaseHas('event_requirement_questions', ['question_code' => 'colours']);
    }

    public function test_question_can_map_selected_values_from_dynamic_vendor_attributes(): void
    {
        $this->createDynamicVendor('Silver Hall', [
            ['key' => 'area', 'label' => 'Area', 'type' => 'dropdown', 'value' => 'Pune', 'images' => ['dynamic-vendors/areas/pune.jpg']],
            ['key' => 'parking', 'label' => 'Parking', 'type' => 'boolean', 'value' => true],
        ]);
        $this->createDynamicVendor('Gold Hall', [
            ['key' => 'area', 'label' => 'Area', 'type' => 'dropdown', 'value' => 'Mumbai'],
            ['key' => 'parking', 'label' => 'Parking', 'type' => 'boolean', 'value' => false],
        ]);

        $this->get(route('admin.event-questions.create'))
            ->assertOk()
            ->assertSee('Dynamic vendor mapping')
            ->assertSee('Pune')
            ->assertSee('Mumbai')
            ->assertSee('Silver Hall')
            ->assertSee('Gold Hall');

        $this->post(route('admin.event-questions.store'), [
            'question' => 'Which area do you prefer?',
            'question_code' => 'preferred_vendor_area',
            'question_type' => 'checkbox',
            'vendor_attribute_key' => 'area',
            'vendor_attribute_values' => ['Pune', 'Mumbai'],
            'vendor_attribute_images' => ['dynamic-vendors/areas/pune.jpg'],
            'display_order' => 50,
            'status' => 1,
        ])->assertRedirect(route('admin.event-questions.index'));

        $question = EventRequirementQuestion::where('question_code', 'preferred_vendor_area')->firstOrFail();
        $this->assertSame('area', $question->vendor_attribute_key);
        $this->assertSame('Area', $question->vendor_attribute_label);
        $this->assertSame(['Pune', 'Mumbai'], $question->vendor_attribute_values);
        $this->assertSame(['Pune', 'Mumbai'], $question->options);
        $this->assertSame(['dynamic-vendors/areas/pune.jpg'], $question->vendor_attribute_images);

        $this->get(route('admin.event-questions.show', $question))
            ->assertOk()
            ->assertSee('Question Overview')
            ->assertSee('Which area do you prefer?')
            ->assertSee('Pune')
            ->assertSee('Mumbai');
    }

    public function test_question_mapping_rejects_a_value_not_present_in_vendor_data(): void
    {
        $this->createDynamicVendor('Silver Hall', [
            ['key' => 'area', 'label' => 'Area', 'type' => 'dropdown', 'value' => 'Pune'],
        ]);

        $this->post(route('admin.event-questions.store'), [
            'question' => 'Which area?',
            'question_code' => 'invalid_area',
            'question_type' => 'checkbox',
            'vendor_attribute_key' => 'area',
            'vendor_attribute_values' => ['Unknown'],
            'display_order' => 51,
            'status' => 1,
        ])->assertSessionHasErrors('vendor_attribute_values');

        $this->assertDatabaseMissing('event_requirement_questions', ['question_code' => 'invalid_area']);
    }

    public function test_question_data_can_be_freshly_rebuilt_from_current_dynamic_vendors(): void
    {
        EventRequirementQuestion::create([
            'question' => 'Legacy question', 'question_code' => 'legacy', 'question_type' => 'textbox',
            'display_order' => 1, 'status' => true,
        ]);
        $this->createDynamicVendor('Silver Hall', [
            ['key' => 'area', 'label' => 'Area', 'type' => 'dropdown', 'value' => 'Pune'],
            ['key' => 'parking', 'label' => 'Parking', 'type' => 'boolean', 'value' => true],
        ]);

        $this->seed(EventRequirementQuestionSeeder::class);

        $this->assertDatabaseMissing('event_requirement_questions', ['question_code' => 'legacy']);
        $this->assertDatabaseHas('event_requirement_questions', ['question_code' => 'wedding_budget', 'question_type' => 'number']);
        $this->assertDatabaseHas('event_requirement_questions', ['question_code' => 'guest_capacity', 'is_required' => true]);
        $this->assertDatabaseHas('event_requirement_questions', ['question_code' => 'decoration_type', 'vendor_attribute_key' => 'decoration_type']);
        $this->assertDatabaseCount('event_requirement_questions', 7);
    }

    public function test_notification_creation_syncs_recipients_using_the_correct_pivot_keys(): void
    {
        $users = User::factory()->count(2)->create();

        $this->post(route('admin.notifications.store'), [
            'title' => 'Service update',
            'message' => 'The platform will be updated tonight.',
            'notification_type' => 'information',
            'recipient_scope' => 'all',
            'status' => 'sent',
        ])->assertRedirect();

        $notification = AdminNotification::firstOrFail();
        $this->assertSame(2, $notification->users()->count());
        foreach ($users as $user) {
            $this->assertDatabaseHas('notification_users', ['notification_id' => $notification->id, 'user_id' => $user->id]);
            $this->assertTrue($user->adminNotifications()->whereKey($notification->id)->exists());
        }
    }

    public function test_admin_can_create_update_view_and_delete_a_sanitized_page(): void
    {
        $this->post(route('admin.pages.store'), [
            'title' => 'About Our Events',
            'slug' => 'about-events',
            'description' => '<h2 onclick="alert(1)">Welcome</h2><p>Plan with us.</p><script>alert(1)</script><a href="javascript:alert(1)">Bad link</a>',
            'status' => 1,
        ])->assertRedirect();

        $page = Page::firstOrFail();
        $this->assertStringContainsString('<h2>Welcome</h2>', $page->description);
        $this->assertStringNotContainsString('onclick', $page->description);
        $this->assertStringNotContainsString('<script', $page->description);
        $this->assertStringNotContainsString('javascript:', $page->description);

        $this->get(route('admin.pages.show', $page))->assertOk()->assertSee('Plan with us.');
        $this->put(route('admin.pages.update', $page), [
            'title' => 'About Our Celebrations',
            'slug' => 'about-celebrations',
            'description' => '<p>Updated content.</p>',
        ])->assertRedirect();
        $this->assertDatabaseHas('pages', ['id' => $page->id, 'slug' => 'about-celebrations', 'status' => false]);

        $this->delete(route('admin.pages.destroy', $page))->assertRedirect(route('admin.pages.index'));
        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_removed_planner_and_prompt_dependencies_are_absent(): void
    {
        foreach (['areas', 'budget_rules', 'cities', 'event_plans', 'master_registries', 'states', 'subareas', 'system_masters', 'ai_prompts'] as $table) {
            $this->assertFalse(Schema::hasTable($table), "The {$table} table should not exist.");
        }

        $this->assertFalse(Route::has('admin.ai-prompts.index'));
        $this->assertFalse(Route::has('user.wizard'));
        $this->get(route('admin.ai.manage'))->assertOk()->assertDontSee('Default Enabled Prompt');
    }

    private function createDynamicVendor(string $name, array $attributes): DynamicVendor
    {
        return DynamicVendor::create([
            'vendor_json' => [
                'schema_version' => 1,
                'identity' => ['name' => $name, 'category' => 'Venue'],
                'attributes' => $attributes,
                'media' => ['images' => []],
                'seo' => [],
            ],
            'status' => 'active',
        ]);
    }
}
