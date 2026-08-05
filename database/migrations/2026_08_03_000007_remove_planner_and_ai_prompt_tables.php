<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('event_plans');

        Schema::dropIfExists('subareas');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');

        Schema::dropIfExists('budget_rules');
        Schema::dropIfExists('master_registries');
        Schema::dropIfExists('system_masters');
        Schema::dropIfExists('ai_prompts');

        if (Schema::hasTable('ai_settings')) {
            DB::table('ai_settings')->where('key', 'default_prompt_id')->delete();
        }

        if (Schema::hasTable('admin_module_options')) {
            DB::table('admin_module_options')->where('group', 'prompt_type')->delete();
        }
    }

    public function down(): void
    {
        // Removed data and obsolete modules cannot be restored automatically.
    }
};
