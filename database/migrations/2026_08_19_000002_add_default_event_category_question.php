<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('event_requirement_questions')->where('question_code', 'event_category')->exists()) {
            return;
        }

        DB::table('event_requirement_questions')->insert([
            'question' => 'What type of event are you planning?',
            'question_code' => 'event_category',
            'question_type' => 'radio',
            'options' => json_encode(['Grand Wedding & Sangeet']),
            'is_required' => true,
            'display_order' => 0,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('event_requirement_questions')
            ->where('question_code', 'event_category')
            ->delete();
    }
};
