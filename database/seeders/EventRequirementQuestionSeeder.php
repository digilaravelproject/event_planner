<?php

namespace Database\Seeders;

use App\Models\EventRequirementQuestion;
use App\Services\VendorAttributeCatalogService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventRequirementQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = app(VendorAttributeCatalogService::class)->catalog();
        $definitions = [
            [
                'question' => 'What is your total estimated wedding budget?',
                'question_code' => 'wedding_budget',
                'question_type' => 'number',
                'options' => ['10', '25', '50', '100'],
                'is_required' => true,
            ],
            [
                'question' => 'How many guests will celebrate with you?',
                'question_code' => 'guest_capacity',
                'question_type' => 'number',
                'options' => ['50', '150', '300', '600'],
                'vendor_attribute_key' => 'guest_capacity',
                'is_required' => true,
            ],
            [
                'question' => 'What type of wedding celebration is this?',
                'question_code' => 'wedding_tradition',
                'question_type' => 'radio',
                'options' => ['Maharashtrian Lagna', 'Muslim Nikah & Walima', 'North Indian / Punjabi', 'South Indian Tradition', 'Gujarati Garba Shaadi', 'Marwari / Rajputana Royal', 'Catholic / Christian Wedding', 'Fusion / Modern Minimalist'],
                'is_required' => true,
            ],
            [
                'question' => 'Select your venue vibe and mandap decor',
                'question_code' => 'decoration_type',
                'question_type' => 'radio',
                'options' => ['Traditional Marigold & Brass', 'Arabian Emerald & Crystal Glow', 'Royal Red & Gold Palace Mandap', 'Temple Lotus & White Mogra'],
                'vendor_attribute_key' => 'decoration_type',
                'is_required' => true,
            ],
            [
                'question' => 'What are your catering preferences?',
                'question_code' => 'food_type',
                'question_type' => 'radio',
                'options' => ['Pure Vegetarian / Jain', 'Veg & Non-Veg Multi-Cuisine', 'Gourmet Luxury Live Counters'],
                'vendor_attribute_key' => 'menu_card_items',
                'is_required' => true,
            ],
            [
                'question' => 'Where would you like to host?',
                'question_code' => 'service_area',
                'question_type' => 'radio',
                'options' => ['Juhu / Bandra Sea-Face', 'South Mumbai Heritage', 'Suburban AC Banquets', 'Thane & Navi Mumbai'],
                'vendor_attribute_key' => 'service_area',
                'is_required' => true,
            ],
            [
                'question' => 'When is the big day planned?',
                'question_code' => 'event_timeline',
                'question_type' => 'radio',
                'options' => ['Next 3 Months', '3 - 6 Months', '6+ Months Ahead'],
                'is_required' => true,
            ],
        ];

        DB::transaction(function () use ($definitions, $catalog): void {
            EventRequirementQuestion::query()->delete();

            foreach ($definitions as $index => $definition) {
                $attributeKey = $definition['vendor_attribute_key'] ?? null;
                $attribute = $attributeKey ? ($catalog[$attributeKey] ?? null) : null;
                $mappedValues = $attribute ? array_column($attribute['values'] ?? [], 'value') : [];

                EventRequirementQuestion::create([
                    'question' => $definition['question'],
                    'question_code' => $definition['question_code'],
                    'question_type' => $definition['question_type'],
                    'options' => $definition['options'],
                    'vendor_attribute_key' => $attributeKey,
                    'vendor_attribute_label' => $attribute['label'] ?? null,
                    'vendor_attribute_values' => $mappedValues,
                    'vendor_attribute_images' => $attribute['images'] ?? [],
                    'is_required' => $definition['is_required'],
                    'display_order' => $index + 1,
                    'status' => true,
                ]);
            }
        });

        $this->command?->info('Seven wedding planning questions seeded.');
    }
}
