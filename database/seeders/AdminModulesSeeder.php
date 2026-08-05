<?php

namespace Database\Seeders;

use App\Models\AdminModuleOption;
use App\Models\AiSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminModulesSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'ai_model' => ['gpt-4o', 'gpt-4.1', 'gpt-4.1-mini', 'gpt-5'],
            'question_type' => ['Textbox', 'Textarea', 'Dropdown', 'Radio', 'Checkbox', 'Number', 'Date'],
            'notification_type' => ['Information', 'Success', 'Warning', 'Promotion', 'Reminder'],
            'feedback_status' => ['Pending', 'Reviewed', 'Resolved', 'Rejected'],
        ];
        foreach ($groups as $group => $values) {
            foreach ($values as $order => $label) {
                AdminModuleOption::updateOrCreate(['group' => $group, 'value' => Str::slug($label, '_')], ['label' => $label, 'display_order' => $order + 1, 'status' => true]);
            }
        }
        // Model API identifiers retain dots and hyphens.
        foreach ($groups['ai_model'] as $order => $model) {
            AdminModuleOption::updateOrCreate(['group' => 'ai_model', 'value' => $model], ['label' => $model, 'display_order' => $order + 1, 'status' => true]);
        }
        AdminModuleOption::where('group', 'ai_model')->whereNotIn('value', $groups['ai_model'])->delete();

        $this->call(EventRequirementQuestionSeeder::class);

        AiSetting::setValue('openai_model', AiSetting::getValue('openai_model', 'gpt-4o'));
        AiSetting::setValue('status', AiSetting::getValue('status', true));
    }
}
