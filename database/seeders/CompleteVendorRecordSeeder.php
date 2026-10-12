<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\VendorAccount;
use App\Modules\DynamicVendors\Http\Requests\DynamicVendorRequest;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Modules\DynamicVendors\Services\DynamicVendorService;
use Illuminate\Database\Seeder;

class CompleteVendorRecordSeeder extends Seeder
{
    public function run(): void
    {
        $name = 'Complete Attribute Showcase';
        if (DynamicVendor::query()->get()->contains(fn (DynamicVendor $vendor) => $vendor->name === $name)) {
            $this->command?->info('Complete vendor record already exists.');
            return;
        }

        $values = [
            'text' => 'Premium wedding service', 'textarea' => 'A complete long-form service description with every detail.',
            'number' => '500', 'currency' => '250000', 'dropdown' => 'Premium', 'multi_select' => 'Wedding, Reception, Sangeet',
            'checkbox' => true, 'radio' => 'Indoor', 'date' => '2026-12-12', 'time' => '18:30', 'datetime' => '2026-12-12 18:30:00',
            'url' => 'https://example.com/complete-vendor', 'email' => 'complete.vendor@example.com', 'phone' => '+91 9876543210',
            'boolean' => true, 'color' => '#850625', 'image' => 'demo-vendors/decor-mandap.svg', 'file' => 'sample-brochure.pdf',
            'video' => 'https://example.com/showreel.mp4', 'gps' => '18.5204,73.8567', 'rich_text' => '<p>Royal, elegant and fully customisable event services.</p>',
            'json' => '{"team_size":25,"languages":["English","Hindi","Marathi"]}',
        ];
        $attributes = [];
        foreach (DynamicVendorRequest::TYPES as $type) {
            $attributes[] = [
                'label' => ucwords(str_replace('_', ' ', $type)).' Example', 'type' => $type, 'value' => $values[$type],
                'required' => true, 'min_length' => in_array($type, ['text', 'textarea'], true) ? 2 : null,
                'max_length' => in_array($type, ['text', 'textarea'], true) ? 2000 : null,
                'min_value' => in_array($type, ['number', 'currency'], true) ? 0 : null,
                'max_value' => in_array($type, ['number', 'currency'], true) ? 5000000 : null,
                'allowed_values' => in_array($type, ['dropdown', 'multi_select', 'radio'], true) ? 'Basic, Premium, Wedding, Reception, Sangeet, Indoor, Outdoor' : null,
                'default_value' => $values[$type],
            ];
        }

        app(DynamicVendorService::class)->create([
            'name' => $name, 'category' => 'Full Service Event Planner', 'status' => 'active', 'attributes' => $attributes,
            'short_description' => 'One complete vendor record containing every supported attribute type and a populated value.',
            'description' => 'A ready-to-review record created for validating all dynamic vendor fields in the admin and vendor panels.',
            'tags' => 'complete, showcase, all attributes', 'keywords' => 'vendor attributes, sample values, event planner',
            'offerings' => [['name' => 'Complete Wedding Package', 'category' => 'Wedding', 'min_capacity' => 50, 'max_capacity' => 2000, 'min_budget' => 5, 'max_budget' => 100, 'locations' => 'Pune, Mumbai, Nashik', 'traditions' => 'Maharashtrian, Punjabi, Gujarati, South Indian', 'notes' => 'All planning services included.']],
            'food_packages' => [['name' => 'Complete Celebration Menu', 'min_price_per_plate' => 900, 'max_price_per_plate' => 1500, 'tagline' => 'Full menu with live counters', 'items' => 'Starters, Main Course, Dessert, Live Counters']],
            'food_extras' => [['name' => 'Live Dessert Counter', 'min_price' => 100, 'max_price' => 180, 'unit' => 'per_plate', 'icon' => 'fa-ice-cream']],
        ], [], Admin::query()->value('id'), VendorAccount::query()->value('id'));

        $this->command?->info('Complete vendor record created with '.count($attributes).' populated attributes.');
    }
}
