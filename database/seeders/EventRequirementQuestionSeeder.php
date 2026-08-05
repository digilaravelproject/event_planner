<?php

namespace Database\Seeders;

use App\Models\EventRequirementQuestion;
use App\Services\VendorAttributeCatalogService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventRequirementQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = app(VendorAttributeCatalogService::class)->catalog();

        DB::transaction(function () use ($catalog): void {
            EventRequirementQuestion::query()->delete();

            $displayOrder = 1;
            foreach ($catalog as $attribute) {
                if ($attribute['values'] === []) {
                    continue;
                }

                EventRequirementQuestion::create([
                    'question' => $this->questionFor($attribute['label'], $attribute['type']),
                    'question_code' => Str::limit('vendor_'.$attribute['key'], 100, ''),
                    'question_type' => $this->questionTypeFor($attribute['type']),
                    'placeholder' => null,
                    'options' => array_column($attribute['values'], 'label'),
                    'vendor_attribute_key' => $attribute['key'],
                    'vendor_attribute_label' => $attribute['label'],
                    'vendor_attribute_values' => array_column($attribute['values'], 'value'),
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
