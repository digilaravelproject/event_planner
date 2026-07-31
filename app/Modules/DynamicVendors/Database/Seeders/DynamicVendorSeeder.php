<?php

namespace App\Modules\DynamicVendors\Database\Seeders;

use App\Models\Admin;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Modules\DynamicVendors\Services\DynamicVendorService;
use Illuminate\Database\Seeder;

class DynamicVendorSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(DynamicVendorService::class);
        $adminId = Admin::query()->value('id');
        $existingVendors = DynamicVendor::query()->get()->keyBy('name');
        $brands = ['Royal', 'Emerald', 'Celebration', 'Signature', 'Elite', 'Shree', 'Grand', 'Prime', 'Bliss', 'Urban'];
        $cities = ['Pune', 'Mumbai', 'Nashik', 'Nagpur'];
        $profiles = $this->profiles();
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($profiles as $profileIndex => $profile) {
            foreach ($brands as $brandIndex => $brand) {
                $name = "$brand {$profile['suffix']}";
                $city = $cities[($profileIndex + $brandIndex) % count($cities)];
                $sequence = ($profileIndex * count($brands)) + $brandIndex + 1;
                $attributes = [
                    $this->attribute('Price', 'currency', $profile['price'] + ($brandIndex * 5000), $profile['category'], required: true, minValue: 0),
                    $this->attribute('Service Area', 'dropdown', $city, $profile['category'], required: true, allowedValues: $cities),
                    $this->attribute('Contact Phone', 'phone', '+91 98'.str_pad((string) (10000000 + $sequence), 8, '0', STR_PAD_LEFT), $profile['category'], required: true),
                    $this->attribute('Currently Available', 'boolean', $sequence % 5 !== 0, $profile['category'], required: true),
                ];

                foreach ($profile['attributes'] as $definition) {
                    $value = $this->resolveValue($definition['value'], $brandIndex, $city, $sequence);
                    $allowedValues = $definition['allowed'] ?? $this->deriveAllowedValues($definition);
                    $attributes[] = $this->attribute(
                        $definition['label'],
                        $definition['type'],
                        $value,
                        $profile['category'],
                        $definition['required'] ?? false,
                        $allowedValues,
                    );
                }

                $payload = [
                    'name' => $name,
                    'category' => $profile['category'],
                    'status' => $sequence % 11 === 0 ? 'draft' : ($sequence % 9 === 0 ? 'inactive' : 'active'),
                    'attributes' => $attributes,
                    'short_description' => "$name provides professional {$profile['category']} services in $city.",
                    'description' => "Sample {$profile['category']} vendor serving events across $city and nearby areas. This record demonstrates typed, AI-ready dynamic attributes.",
                    'tags' => strtolower($profile['category']).", $city, event vendor",
                    'keywords' => "$name, {$profile['category']}, $city, event planning",
                ];

                $existing = $existingVendors->get($name);
                if ($existing === null) {
                    $service->create($payload, [], $adminId);
                    $created++;
                } elseif ($this->needsGuidanceUpdate($existing, $payload)) {
                    $service->update($existing, $payload, [], $adminId);
                    $updated++;
                } else {
                    $skipped++;
                }
            }
        }

        $this->command?->info("Dynamic vendor data: $created created, $updated updated, $skipped already current.");
    }

    private function profiles(): array
    {
        return [
            ['category' => 'Venue', 'suffix' => 'Celebration Lawns', 'price' => 180000, 'attributes' => [
                ['label' => 'Guest Capacity', 'type' => 'number', 'value' => [500, 800, 1200, 1600], 'required' => true],
                ['label' => 'Parking', 'type' => 'boolean', 'value' => [true, true, false, true]],
                ['label' => 'Rooms', 'type' => 'number', 'value' => [8, 14, 20, 30]],
                ['label' => 'GPS Location', 'type' => 'gps', 'value' => ['18.5204, 73.8567', '19.0760, 72.8777', '19.9975, 73.7898', '21.1458, 79.0882']],
            ]],
            ['category' => 'Photographer', 'suffix' => 'Wedding Stories', 'price' => 45000, 'attributes' => [
                ['label' => 'Drone', 'type' => 'boolean', 'value' => [true, true, false, true]],
                ['label' => 'Album', 'type' => 'dropdown', 'value' => ['Premium', 'Luxury', 'Classic', 'Premium'], 'allowed' => ['Classic', 'Premium', 'Luxury']],
                ['label' => 'Delivery Time', 'type' => 'text', 'value' => ['15 Days', '20 Days', '12 Days', '18 Days']],
                ['label' => 'Photography Styles', 'type' => 'multi_select', 'value' => ['Candid, Traditional', 'Cinematic, Candid', 'Traditional', 'Candid, Drone']],
            ]],
            ['category' => 'Decorator', 'suffix' => 'Decor Studio', 'price' => 65000, 'attributes' => [
                ['label' => 'Decoration Theme', 'type' => 'dropdown', 'value' => ['Floral', 'Royal', 'Minimal', 'Bohemian'], 'allowed' => ['Floral', 'Royal', 'Minimal', 'Bohemian']],
                ['label' => 'Stage Included', 'type' => 'boolean', 'value' => [true, true, true, false]],
                ['label' => 'Primary Colour', 'type' => 'color', 'value' => ['#f4b6c2', '#d4af37', '#f5f5dc', '#c97b63']],
            ]],
            ['category' => 'DJ', 'suffix' => 'DJ & Sound', 'price' => 30000, 'attributes' => [
                ['label' => 'Sound Power', 'type' => 'text', 'value' => ['5000W', '8000W', '10000W', '6500W']],
                ['label' => 'Lighting', 'type' => 'dropdown', 'value' => ['Premium', 'Laser', 'Standard', 'Concert'], 'allowed' => ['Standard', 'Premium', 'Laser', 'Concert']],
                ['label' => 'Smoke Machine', 'type' => 'boolean', 'value' => [true, true, false, true]],
                ['label' => 'Performance Start', 'type' => 'time', 'value' => ['19:00', '20:00', '18:30', '21:00']],
            ]],
            ['category' => 'Catering', 'suffix' => 'Catering Company', 'price' => 90000, 'attributes' => [
                ['label' => 'Food Type', 'type' => 'multi_select', 'value' => ['Veg, Jain', 'Veg, Non Veg', 'Veg', 'Veg, Non Veg, Jain']],
                ['label' => 'Cuisine', 'type' => 'multi_select', 'value' => ['Maharashtrian, Punjabi', 'Indian, Continental', 'South Indian, Indian', 'Indian, Chinese']],
                ['label' => 'Minimum Plates', 'type' => 'number', 'value' => [100, 200, 75, 150]],
                ['label' => 'Live Counters', 'type' => 'boolean', 'value' => [true, true, false, true]],
            ]],
            ['category' => 'Hotel', 'suffix' => 'Grand Hotel', 'price' => 120000, 'attributes' => [
                ['label' => 'Rooms', 'type' => 'number', 'value' => [40, 80, 25, 60]],
                ['label' => 'Check In', 'type' => 'time', 'value' => ['12:00', '14:00', '11:00', '13:00']],
                ['label' => 'Check Out', 'type' => 'time', 'value' => ['10:00', '11:00', '10:30', '11:00']],
                ['label' => 'Hotel Website', 'type' => 'url', 'value' => ['https://example.com/hotel/royal', 'https://example.com/hotel/emerald', 'https://example.com/hotel/celebration', 'https://example.com/hotel/signature']],
            ]],
            ['category' => 'Makeup', 'suffix' => 'Bridal Makeup', 'price' => 25000, 'attributes' => [
                ['label' => 'Makeup Style', 'type' => 'multi_select', 'value' => ['HD, Bridal', 'Airbrush, HD', 'Natural, Bridal', 'HD, Editorial']],
                ['label' => 'Trial Included', 'type' => 'boolean', 'value' => [true, false, true, true]],
                ['label' => 'Products Used', 'type' => 'textarea', 'value' => ['MAC and Huda Beauty', 'Kryolan and MAC', 'Premium cruelty-free brands', 'International luxury brands']],
            ]],
            ['category' => 'Transport', 'suffix' => 'Wedding Transport', 'price' => 35000, 'attributes' => [
                ['label' => 'Vehicle Types', 'type' => 'multi_select', 'value' => ['Sedan, SUV', 'Luxury Car, Bus', 'Vintage Car', 'SUV, Mini Bus']],
                ['label' => 'Fleet Size', 'type' => 'number', 'value' => [12, 25, 8, 18]],
                ['label' => 'Driver Included', 'type' => 'boolean', 'value' => [true, true, true, true]],
            ]],
            ['category' => 'Florist', 'suffix' => 'Floral Art', 'price' => 40000, 'attributes' => [
                ['label' => 'Flower Selection', 'type' => 'multi_select', 'value' => ['Rose, Orchid', 'Lily, Rose', 'Marigold, Jasmine', 'Tulip, Orchid']],
                ['label' => 'Fresh Flowers', 'type' => 'boolean', 'value' => [true, true, true, true]],
                ['label' => 'Design Notes', 'type' => 'rich_text', 'value' => ['Elegant pastel arrangements', 'Luxury cascading installations', 'Traditional festive arrangements', 'Modern sculptural floral design']],
            ]],
            ['category' => 'Entertainment', 'suffix' => 'Live Entertainment', 'price' => 55000, 'attributes' => [
                ['label' => 'Act Types', 'type' => 'multi_select', 'value' => ['Singer, Band', 'Dance, Anchor', 'Comedy, Magic', 'Band, Folk Dance']],
                ['label' => 'Performance Duration', 'type' => 'text', 'value' => ['3 Hours', '4 Hours', '2 Hours', '3.5 Hours']],
                ['label' => 'Technical Rider', 'type' => 'json', 'value' => ['{"mics":4,"monitors":2}', '{"mics":6,"monitors":4}', '{"mics":2,"monitors":1}', '{"mics":5,"monitors":3}']],
            ]],
            ['category' => 'Pandit', 'suffix' => 'Vedic Services', 'price' => 15000, 'attributes' => [
                ['label' => 'Ceremony Types', 'type' => 'multi_select', 'value' => ['Wedding, Ganesh Puja', 'Wedding, Grah Shanti', 'Wedding', 'Wedding, Satyanarayan Puja']],
                ['label' => 'Languages', 'type' => 'multi_select', 'value' => ['Marathi, Hindi', 'Hindi, Gujarati', 'Marathi, Sanskrit', 'Hindi, English']],
                ['label' => 'Samagri Included', 'type' => 'boolean', 'value' => [true, true, false, true]],
            ]],
            ['category' => 'Mehendi', 'suffix' => 'Mehendi Artists', 'price' => 18000, 'attributes' => [
                ['label' => 'Design Styles', 'type' => 'multi_select', 'value' => ['Arabic, Bridal', 'Rajasthani, Bridal', 'Minimal, Arabic', 'Portrait, Bridal']],
                ['label' => 'Artist Count', 'type' => 'number', 'value' => [3, 5, 2, 4]],
                ['label' => 'Organic Henna', 'type' => 'boolean', 'value' => [true, true, true, true]],
            ]],
            ['category' => 'Invitation', 'suffix' => 'Invitation Design', 'price' => 12000, 'attributes' => [
                ['label' => 'Invitation Types', 'type' => 'multi_select', 'value' => ['Printed, Digital', 'Luxury Box, Printed', 'Digital', 'Printed, Video']],
                ['label' => 'Minimum Quantity', 'type' => 'number', 'value' => [50, 100, 25, 75]],
                ['label' => 'Digital Preview', 'type' => 'boolean', 'value' => [true, true, true, true]],
            ]],
            ['category' => 'Jewellery', 'suffix' => 'Bridal Jewellery', 'price' => 85000, 'attributes' => [
                ['label' => 'Jewellery Types', 'type' => 'multi_select', 'value' => ['Gold, Kundan', 'Diamond, Gold', 'Temple, Gold', 'Polki, Kundan']],
                ['label' => 'Rental Available', 'type' => 'boolean', 'value' => [true, false, true, true]],
                ['label' => 'Consultation Email', 'type' => 'email', 'value' => ['royal@example.com', 'emerald@example.com', 'celebration@example.com', 'signature@example.com']],
            ]],
            ['category' => 'Travel', 'suffix' => 'Honeymoon Travel', 'price' => 70000, 'attributes' => [
                ['label' => 'Destinations', 'type' => 'multi_select', 'value' => ['Goa, Kerala', 'Maldives, Bali', 'Kashmir, Himachal', 'Europe, Dubai']],
                ['label' => 'Trip Duration', 'type' => 'text', 'value' => ['5 Days', '7 Days', '6 Days', '10 Days']],
                ['label' => 'Travel Date', 'type' => 'date', 'value' => ['2026-11-15', '2026-12-01', '2027-01-10', '2027-02-14']],
            ]],
        ];
    }

    private function attribute(
        string $label,
        string $type,
        mixed $value,
        string $category,
        bool $required = false,
        array $allowedValues = [],
        int|float|null $minValue = null,
    ): array {
        $isNumeric = in_array($type, ['number', 'currency'], true);
        $isLongText = in_array($type, ['textarea', 'rich_text', 'json'], true);
        $isTextual = in_array($type, ['text', 'textarea', 'rich_text', 'phone', 'email', 'url'], true);

        return [
            'label' => $label,
            'type' => $type,
            'value' => is_array($value) ? implode(', ', $value) : $value,
            'required' => $required,
            'allowed_values' => implode(', ', $allowedValues),
            'min_length' => $isTextual ? ($type === 'phone' ? 10 : 2) : null,
            'max_length' => $isTextual ? ($isLongText ? 2000 : ($type === 'phone' ? 20 : 255)) : null,
            'min_value' => $isNumeric ? ($minValue ?? 0) : null,
            'max_value' => $isNumeric ? $this->maximumFor($label, $value) : null,
            'placeholder' => $this->placeholderFor($label, $type),
            'help_text' => $this->explanationFor($label, $category),
            'default_value' => $this->defaultFor($type, $allowedValues),
        ];
    }

    private function deriveAllowedValues(array $definition): array
    {
        if (($definition['type'] ?? null) !== 'multi_select') {
            return [];
        }

        return collect($definition['value'] ?? [])
            ->flatMap(fn ($value) => array_map('trim', explode(',', (string) $value)))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function maximumFor(string $label, mixed $value): int|float
    {
        return match ($label) {
            'Price' => 2000000,
            'Guest Capacity' => 5000,
            'Rooms' => 500,
            'Minimum Plates' => 5000,
            'Fleet Size' => 500,
            'Artist Count' => 50,
            'Minimum Quantity' => 10000,
            default => max(100, (float) $value * 5),
        };
    }

    private function placeholderFor(string $label, string $type): string
    {
        return match ($type) {
            'dropdown' => "Select $label",
            'multi_select' => "Select one or more $label",
            'boolean', 'checkbox' => "Choose yes or no for $label",
            'currency' => "Enter $label in INR",
            'number' => "Enter numeric $label",
            'date' => 'Select date',
            'time' => 'Select time',
            'email' => 'name@example.com',
            'phone' => '+91 98765 43210',
            'url' => 'https://example.com',
            'gps' => '18.5204, 73.8567',
            'color' => '#d4af37',
            'json' => '{"key":"value"}',
            default => "Enter $label",
        };
    }

    private function defaultFor(string $type, array $allowedValues): mixed
    {
        return match ($type) {
            'boolean', 'checkbox' => false,
            'number', 'currency' => 0,
            'dropdown', 'radio', 'multi_select' => $allowedValues[0] ?? null,
            default => null,
        };
    }

    private function explanationFor(string $label, string $category): string
    {
        $specific = [
            'Price' => 'Required for budget matching, quotations, price-range filtering, and AI recommendations.',
            'Service Area' => 'Identifies where the vendor operates so location-based searches return relevant results.',
            'Contact Phone' => 'Provides an operational contact number for enquiries and booking coordination.',
            'Currently Available' => 'Prevents unavailable vendors from being recommended for new enquiries.',
            'Guest Capacity' => 'Ensures the venue can safely accommodate the expected number of guests.',
            'Parking' => 'Helps planners evaluate guest convenience and vehicle accommodation.',
            'Rooms' => 'Shows on-site accommodation capacity for hosts, guests, or event staff.',
            'GPS Location' => 'Provides precise coordinates for maps, distance calculations, and nearby-vendor search.',
            'Drone' => 'Indicates whether aerial photography or videography can be included.',
            'Album' => 'Defines the physical album quality included in the photography package.',
            'Delivery Time' => 'Sets expectations for receiving edited photos, videos, or final deliverables.',
            'Photography Styles' => 'Helps clients match the photographer’s creative approach to their preferences.',
            'Decoration Theme' => 'Describes the visual theme the decorator can deliver for the event.',
            'Stage Included' => 'Clarifies whether stage design and setup are included in the quoted package.',
            'Primary Colour' => 'Records the main design colour for visual coordination and theme matching.',
            'Sound Power' => 'Indicates whether the audio system is powerful enough for the venue and audience size.',
            'Lighting' => 'Describes the lighting package used to create the required event atmosphere.',
            'Smoke Machine' => 'Clarifies availability of a common stage effect that may require venue approval.',
            'Performance Start' => 'Supports event timeline planning and performer scheduling.',
            'Food Type' => 'Allows dietary and cultural food preferences to be matched accurately.',
            'Cuisine' => 'Helps users find caterers that serve their preferred regional or international menus.',
            'Minimum Plates' => 'Defines the smallest catering order the vendor will accept.',
            'Live Counters' => 'Indicates whether interactive food stations can be provided.',
            'Check In' => 'Communicates when hotel rooms become available to event guests.',
            'Check Out' => 'Communicates the room departure deadline for itinerary planning.',
            'Hotel Website' => 'Provides an authoritative page for facilities, policies, and accommodation details.',
            'Makeup Style' => 'Matches the artist’s supported techniques with the client’s desired bridal look.',
            'Trial Included' => 'Clarifies whether a pre-event makeup trial is part of the package.',
            'Products Used' => 'Helps clients assess product quality, allergies, sensitivities, and brand preferences.',
            'Vehicle Types' => 'Shows which transport options are available for guests and the wedding party.',
            'Fleet Size' => 'Indicates how many vehicles can be supplied for coordinated transport.',
            'Driver Included' => 'Clarifies whether professional drivers are included in the transport price.',
            'Flower Selection' => 'Shows available flower varieties for matching season, theme, and budget.',
            'Fresh Flowers' => 'Distinguishes fresh floral work from artificial or mixed arrangements.',
            'Design Notes' => 'Explains the florist’s design approach and expected visual style.',
            'Act Types' => 'Identifies the entertainment formats available for different audiences and schedules.',
            'Performance Duration' => 'Supports timeline planning and comparison of entertainment packages.',
            'Technical Rider' => 'Records structured equipment requirements for production and venue teams.',
            'Ceremony Types' => 'Shows which religious ceremonies and rituals the officiant can conduct.',
            'Languages' => 'Ensures ceremonies and communication are delivered in suitable languages.',
            'Samagri Included' => 'Clarifies whether ritual materials are supplied or must be arranged separately.',
            'Design Styles' => 'Matches the mehendi artist’s expertise with the client’s preferred design style.',
            'Artist Count' => 'Helps estimate how many guests can be served within the available time.',
            'Organic Henna' => 'Indicates use of natural henna for safety and stain-quality considerations.',
            'Invitation Types' => 'Shows whether printed, digital, video, or boxed invitation formats are offered.',
            'Minimum Quantity' => 'Defines the smallest invitation production order the vendor accepts.',
            'Digital Preview' => 'Confirms that artwork can be reviewed and approved before final production.',
            'Jewellery Types' => 'Helps clients filter jewellery by material, craftsmanship, and bridal style.',
            'Rental Available' => 'Clarifies whether jewellery can be hired instead of purchased.',
            'Consultation Email' => 'Provides a dedicated channel for design consultations and appointment requests.',
            'Destinations' => 'Shows the locations for which the travel vendor can plan and book packages.',
            'Trip Duration' => 'Communicates the package length for leave, itinerary, and budget planning.',
            'Travel Date' => 'Supports availability checks, seasonal pricing, and itinerary preparation.',
        ];

        return $specific[$label] ?? "Provides essential $category information used for comparison, filtering, and accurate event planning.";
    }

    private function needsGuidanceUpdate(DynamicVendor $vendor, array $payload): bool
    {
        if ($vendor->status !== $payload['status'] || count(data_get($vendor->vendor_json, 'attributes', [])) !== count($payload['attributes'])) {
            return true;
        }

        $current = collect(data_get($vendor->vendor_json, 'attributes', []))->keyBy('label');
        foreach ($payload['attributes'] as $attribute) {
            $saved = $current->get($attribute['label']);
            if ($saved === null
                || data_get($saved, 'validation.help_text') !== $attribute['help_text']
                || data_get($saved, 'validation.placeholder') !== $attribute['placeholder']
                || data_get($saved, 'validation.default_value') !== $attribute['default_value']) {
                return true;
            }
        }

        return false;
    }

    private function resolveValue(mixed $value, int $brandIndex, string $city, int $sequence): mixed
    {
        if (is_array($value)) {
            return $value[$brandIndex % count($value)];
        }

        return str_replace(['{city}', '{sequence}'], [$city, (string) $sequence], (string) $value);
    }
}
