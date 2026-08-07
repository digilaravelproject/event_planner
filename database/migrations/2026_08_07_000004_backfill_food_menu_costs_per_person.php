<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $question = DB::table('event_requirement_questions')->where('question_code', 'food_type')->first();
        if (! $question) {
            return;
        }

        $values = json_decode((string) $question->vendor_attribute_values, true) ?: [];
        $metadata = json_decode((string) $question->option_metadata, true) ?: [];
        $defaults = [
            'Butter Chicken' => ['category' => 'Main Course', 'cost' => 350],
            'Gulab Jamun' => ['category' => 'Desserts', 'cost' => 60],
            'Hakka Noodles' => ['category' => 'Live Counters', 'cost' => 180],
            'Masala Dosa' => ['category' => 'Live Counters', 'cost' => 140],
            'Paneer Tikka' => ['category' => 'Starters', 'cost' => 220],
            'Veg Biryani' => ['category' => 'Main Course', 'cost' => 180],
        ];

        foreach ($values as $value) {
            $saved = (array) ($metadata[$value] ?? []);
            $fallback = $defaults[$value] ?? ['category' => 'Menu Items', 'cost' => 100];
            $metadata[$value] = [
                'label' => (string) ($saved['label'] ?? $value),
                'category' => (string) ($saved['category'] ?? $fallback['category']),
                'cost' => (float) (($saved['cost'] ?? 0) > 0 ? $saved['cost'] : $fallback['cost']),
            ];
        }

        DB::table('event_requirement_questions')->where('id', $question->id)->update([
            'options' => json_encode(array_values($values), JSON_UNESCAPED_UNICODE),
            'option_metadata' => json_encode($metadata, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function down(): void
    {
        // Menu prices are administrator-editable business data and are intentionally preserved.
    }
};
