<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\EventRequirementQuestion;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Database\Seeders\AdminModulesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
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
        foreach (['admin.vendor-analytics.index', 'admin.ai.manage', 'admin.event-questions.index', 'admin.notifications.index', 'admin.feedback.index'] as $route) {
            $this->get(route($route))->assertOk();
        }
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
            ['key' => 'area', 'label' => 'Area', 'type' => 'dropdown', 'value' => 'Pune'],
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
            ->assertSee('Mumbai');

        $this->post(route('admin.event-questions.store'), [
            'question' => 'Which area do you prefer?',
            'question_code' => 'preferred_vendor_area',
            'question_type' => 'checkbox',
            'vendor_attribute_key' => 'area',
            'vendor_attribute_values' => ['Pune', 'Mumbai'],
            'display_order' => 50,
            'status' => 1,
        ])->assertRedirect(route('admin.event-questions.index'));

        $question = EventRequirementQuestion::where('question_code', 'preferred_vendor_area')->firstOrFail();
        $this->assertSame('area', $question->vendor_attribute_key);
        $this->assertSame('Area', $question->vendor_attribute_label);
        $this->assertSame(['Pune', 'Mumbai'], $question->vendor_attribute_values);
        $this->assertSame(['Pune', 'Mumbai'], $question->options);
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
