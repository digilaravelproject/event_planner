<?php

namespace Database\Seeders;

use App\Models\AdminModuleOption;
use App\Models\AiSetting;
use App\Models\EventRequirementQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminModulesSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'ai_model' => ['gpt-4o','gpt-4.1','gpt-4.1-mini','gpt-5'],
            'question_type' => ['Textbox','Textarea','Dropdown','Radio','Checkbox','Number','Date'],
            'notification_type' => ['Information','Success','Warning','Promotion','Reminder'],
            'feedback_status' => ['Pending','Reviewed','Resolved','Rejected'],
        ];
        foreach ($groups as $group => $values) foreach ($values as $order => $label) {
            AdminModuleOption::updateOrCreate(['group'=>$group,'value'=>Str::slug($label,'_')],['label'=>$label,'display_order'=>$order+1,'status'=>true]);
        }
        // Model API identifiers retain dots and hyphens.
        foreach ($groups['ai_model'] as $order => $model) AdminModuleOption::updateOrCreate(['group'=>'ai_model','value'=>$model],['label'=>$model,'display_order'=>$order+1,'status'=>true]);
        AdminModuleOption::where('group','ai_model')->whereNotIn('value',$groups['ai_model'])->delete();

        $questions = [
            ['What type of event?','event_type','dropdown',['Wedding','Birthday','Corporate Event','Anniversary']],
            ['How many guests?','guest_count','number',null],
            ['Expected Budget?','expected_budget','number',null],
            ['Preferred City?','preferred_city','textbox',null],
            ['Preferred Date?','preferred_date','date',null],
            ['Venue Type?','venue_type','dropdown',['Indoor','Outdoor','Banquet Hall','Hotel','Lawn']],
            ['Food Preference?','food_preference','radio',['Vegetarian','Non-Vegetarian','Vegan','Mixed']],
            ['Decoration Theme?','decoration_theme','textbox',null],
            ['Need Photography?','photography_required','radio',['Yes','No']],
            ['Need DJ?','dj_required','radio',['Yes','No']],
            ['Need Accommodation?','accommodation_required','radio',['Yes','No']],
            ['Need Transportation?','transportation_required','radio',['Yes','No']],
        ];
        foreach ($questions as $order => [$question,$code,$type,$options]) EventRequirementQuestion::updateOrCreate(['question_code'=>$code],['question'=>$question,'question_type'=>$type,'placeholder'=>null,'options'=>$options,'is_required'=>$order<5,'display_order'=>$order+1,'status'=>true]);

        AiSetting::setValue('openai_model', AiSetting::getValue('openai_model','gpt-4o'));
        AiSetting::setValue('status', AiSetting::getValue('status',true));
    }
}
