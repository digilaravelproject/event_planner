<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\EventRequirementQuestion;
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
        foreach (['admin.vendor-analytics.index','admin.ai.manage','admin.event-questions.index','admin.notifications.index','admin.feedback.index'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_question_resource_validates_and_persists(): void
    {
        $this->post(route('admin.event-questions.store'), [
            'question'=>'Which colours?','question_code'=>'colours','question_type'=>'checkbox',
            'options_text'=>"Red\nBlue",'display_order'=>99,'is_required'=>1,'status'=>1,
        ])->assertRedirect(route('admin.event-questions.index'));
        $this->assertDatabaseHas('event_requirement_questions',['question_code'=>'colours']);
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
}
