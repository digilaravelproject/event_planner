<?php

namespace Database\Seeders;

use App\Models\EventRequirementQuestion;
use App\Services\VendorAttributeCatalogService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventRequirementQuestionSeeder extends Seeder
{
    private const MAX_QUESTIONS = 15;

    private const PRIORITY_ATTRIBUTES = [
        'service_area', 'price', 'currently_available', 'guest_capacity', 'decoration_type',
        'menu_card_items', 'photography_styles', 'lighting', 'makeup_style', 'vehicle_types',
        'flower_selection', 'act_types', 'ceremony_types', 'invitation_types', 'destinations',
    ];

    public function run(): void
    {
        $catalog = app(VendorAttributeCatalogService::class)->catalog();
        $catalog = collect($catalog)
            ->sortBy(fn (array $attribute, string $key): int => ($position = array_search($key, self::PRIORITY_ATTRIBUTES, true)) === false ? 1000 : $position)
            ->filter(fn (array $attribute): bool => $attribute['values'] !== [])
            ->take(self::MAX_QUESTIONS)
            ->all();

        DB::transaction(function () use ($catalog): void {
            EventRequirementQuestion::query()->delete();

            $displayOrder = 1;
            foreach ($catalog as $attribute) {
                EventRequirementQuestion::create([
                    'question' => $this->questionFor($attribute['label'], $attribute['type']),
                    'question_code' => Str::limit('vendor_'.$attribute['key'], 100, ''),
                    'question_type' => $this->questionTypeFor($attribute['type']),
                    'placeholder' => null,
                    'options' => array_column($attribute['values'], 'label'),
                    'vendor_attribute_key' => $attribute['key'],
                    'vendor_attribute_label' => $attribute['label'],
                    'vendor_attribute_values' => array_column($attribute['values'], 'value'),
                    'vendor_attribute_images' => $attribute['images'],
                    'is_required' => false,
                    'display_order' => $displayOrder++,
                    'status' => true,
                ]);
            }
        });

        $this->command?->info(EventRequirementQuestion::count().' event requirement questions rebuilt from current dynamic vendor data.');
    }

    private function questionFor(string $label, string $attributeType): string
    {
        return match ($attributeType) {
            'boolean', 'checkbox' => "Do you require {$label}?",
            'number', 'currency' => "Which {$label} do you prefer?",
            default => "What is your preferred {$label}?",
        };
    }

    private function questionTypeFor(string $attributeType): string
    {
        return match ($attributeType) {
            'boolean', 'radio' => 'radio',
            'multi_select', 'checkbox' => 'checkbox',
            default => 'dropdown',
        };
    }
}
