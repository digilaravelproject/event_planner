<?php

namespace App\Modules\DynamicVendors\Support;

use App\Modules\DynamicVendors\Models\VendorCategory;
use Illuminate\Support\Facades\Schema;

class VendorCategoryFieldCatalog
{
    public static function all(): array
    {
        if (Schema::hasTable('vendor_categories')) {
            return VendorCategory::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn (VendorCategory $category): array => [$category->name => $category->fields ?? []])
                ->all();
        }

        return self::defaults();
    }

    public static function defaults(): array
    {
        $contact = [
            self::field('Contact Person', 'text', 'Who should the team contact?'),
            self::field('Phone Number', 'phone', 'Business contact number'),
            self::field('Email Address', 'email', 'Business email address'),
            self::field('City', 'text', 'Primary city'),
            self::field('Service Areas', 'multi_select', 'Mumbai, Pune, Navi Mumbai'),
        ];

        return [
            'Venue' => array_merge($contact, [
                self::field('Venue Type', 'dropdown', 'Banquet Hall, Lawn, Resort, Hotel, Beachfront', ['Banquet Hall', 'Lawn', 'Resort', 'Hotel', 'Beachfront', 'Heritage Property']),
                self::field('Full Address', 'textarea', 'Complete venue address'),
                self::field('Minimum Guest Capacity', 'number', 'Smallest event size'),
                self::field('Maximum Guest Capacity', 'number', 'Largest event size'),
                self::field('Starting Price', 'currency', 'Starting rental amount', [], 'fixed'),
                self::field('Rooms Available', 'number', 'Number of rooms, if any'),
                self::field('Parking Capacity', 'number', 'Approximate vehicle capacity'),
                self::field('Amenities', 'multi_select', 'AC hall, lawn, pool, bridal room'),
            ]),
            'Photographer' => array_merge($contact, [
                self::field('Photography Styles', 'multi_select', 'Candid, traditional, cinematic'),
                self::field('Starting Package Price', 'currency', 'Starting package amount', [], 'fixed'),
                self::field('Coverage Hours', 'number', 'Hours included in the base package'),
                self::field('Team Size', 'number', 'Photographers and videographers included'),
                self::field('Drone Available', 'boolean', 'Select yes when drone coverage is available'),
                self::field('Delivery Time', 'text', 'Example: 30 working days'),
                self::field('Deliverables', 'multi_select', 'Album, teaser, full film, raw photos'),
            ]),
            'Decorator' => array_merge($contact, [
                self::field('Decoration Styles', 'multi_select', 'Royal, floral, minimal, traditional'),
                self::field('Starting Decor Price', 'currency', 'Starting decor package', [], 'fixed'),
                self::field('Maximum Decor Budget', 'currency', 'Largest package normally handled', [], 'fixed'),
                self::field('Included Services', 'multi_select', 'Stage, mandap, entrance, table decor'),
                self::field('Setup Time', 'text', 'Example: 8 hours before event'),
                self::field('Custom Themes Available', 'boolean', 'Can customers request a custom theme?'),
            ]),
            'Catering' => array_merge($contact, [
                self::field('Cuisines', 'multi_select', 'North Indian, Maharashtrian, Continental'),
                self::field('Food Preferences', 'multi_select', 'Vegetarian, Jain, non-vegetarian, vegan'),
                self::field('Minimum Price Per Plate', 'currency', 'Lowest per-person rate', [], 'per_guest'),
                self::field('Maximum Price Per Plate', 'currency', 'Highest per-person rate', [], 'per_guest'),
                self::field('Minimum Guest Order', 'number', 'Minimum number of guests'),
                self::field('Live Counters', 'multi_select', 'Chaat, dosa, pasta, dessert'),
                self::field('Tasting Available', 'boolean', 'Is a tasting session available?'),
            ]),
            'DJ' => array_merge($contact, [
                self::field('Music Specialities', 'multi_select', 'Bollywood, Punjabi, EDM, retro'),
                self::field('Starting Price', 'currency', 'Starting performance amount', [], 'fixed'),
                self::field('Performance Duration', 'text', 'Example: Up to 5 hours'),
                self::field('Sound System Included', 'boolean', 'Does the package include sound?'),
                self::field('Lighting Included', 'boolean', 'Does the package include lighting?'),
                self::field('Outdoor Events', 'boolean', 'Available for outdoor events?'),
            ]),
            'Makeup' => array_merge($contact, [
                self::field('Services', 'multi_select', 'Bridal makeup, hair, draping, party makeup'),
                self::field('Bridal Package Price', 'currency', 'Starting bridal package', [], 'fixed'),
                self::field('Party Makeup Price', 'currency', 'Price per person', [], 'per_guest'),
                self::field('Trial Available', 'boolean', 'Is a makeup trial available?'),
                self::field('Travels To Venue', 'boolean', 'Can the artist travel to the venue?'),
                self::field('Brands Used', 'multi_select', 'MAC, Huda Beauty, Bobbi Brown'),
            ]),
            'Mehendi' => array_merge($contact, [
                self::field('Design Styles', 'multi_select', 'Arabic, Indian, portrait, minimal'),
                self::field('Bridal Price', 'currency', 'Starting bridal mehendi price', [], 'fixed'),
                self::field('Guest Price', 'currency', 'Price per guest/hand', [], 'per_guest'),
                self::field('Team Size', 'number', 'Number of artists available'),
                self::field('Natural Henna', 'boolean', 'Do you use natural henna?'),
            ]),
            'Hotel' => array_merge($contact, [
                self::field('Star Rating', 'dropdown', 'Select hotel category', ['3 Star', '4 Star', '5 Star', 'Luxury']),
                self::field('Full Address', 'textarea', 'Complete hotel address'),
                self::field('Rooms Available', 'number', 'Rooms available for group booking'),
                self::field('Starting Room Rate', 'currency', 'Price per room/night', [], 'per_day'),
                self::field('Banquet Capacity', 'number', 'Maximum banquet guests'),
                self::field('Amenities', 'multi_select', 'Breakfast, pool, spa, airport pickup'),
            ]),
            'Transport' => array_merge($contact, [
                self::field('Vehicle Types', 'multi_select', 'Car, luxury car, bus, tempo traveller'),
                self::field('Fleet Size', 'number', 'Total vehicles available'),
                self::field('Starting Price', 'currency', 'Starting hire price', [], 'per_day'),
                self::field('Driver Included', 'boolean', 'Is a driver included?'),
                self::field('Outstation Available', 'boolean', 'Can vehicles travel outstation?'),
            ]),
            'Invitation' => array_merge($contact, [
                self::field('Invitation Types', 'multi_select', 'Printed, digital, video, boxed'),
                self::field('Minimum Order Quantity', 'number', 'Smallest printed order'),
                self::field('Starting Price Per Card', 'currency', 'Starting price per invitation', [], 'per_unit'),
                self::field('Design Time', 'text', 'Example: 5 working days'),
                self::field('Custom Design Available', 'boolean', 'Can designs be customised?'),
            ]),
            'Jewellery' => array_merge($contact, [
                self::field('Jewellery Types', 'multi_select', 'Gold, diamond, kundan, imitation'),
                self::field('Rental Available', 'boolean', 'Is jewellery available on rent?'),
                self::field('Starting Price', 'currency', 'Starting purchase/rental amount', [], 'fixed'),
                self::field('Customisation Available', 'boolean', 'Can pieces be customised?'),
                self::field('Appointment Required', 'boolean', 'Is an appointment required?'),
            ]),
            'Travel' => array_merge($contact, [
                self::field('Travel Services', 'multi_select', 'Flights, hotels, honeymoon, group travel'),
                self::field('Destinations', 'multi_select', 'Popular destinations served'),
                self::field('Starting Package Price', 'currency', 'Starting travel package', [], 'fixed'),
                self::field('Visa Assistance', 'boolean', 'Is visa assistance available?'),
                self::field('Emergency Support', 'boolean', 'Is round-the-clock support available?'),
            ]),
            'Pandit' => array_merge($contact, [
                self::field('Ceremonies', 'multi_select', 'Wedding, engagement, griha pravesh'),
                self::field('Languages', 'multi_select', 'Hindi, Marathi, Gujarati, Sanskrit'),
                self::field('Dakshina / Starting Fee', 'currency', 'Starting ceremony fee', [], 'fixed'),
                self::field('Samagri Included', 'boolean', 'Are pooja materials included?'),
                self::field('Travels To Venue', 'boolean', 'Can the priest travel to the venue?'),
            ]),
            'Florist' => array_merge($contact, [
                self::field('Floral Services', 'multi_select', 'Stage, bouquets, varmala, car decor'),
                self::field('Flower Types', 'multi_select', 'Roses, orchids, marigold, imported flowers'),
                self::field('Starting Price', 'currency', 'Starting floral package', [], 'fixed'),
                self::field('Same Day Setup', 'boolean', 'Can setup be completed on event day?'),
            ]),
            'Entertainment' => array_merge($contact, [
                self::field('Entertainment Types', 'multi_select', 'Live band, dancers, anchor, singers'),
                self::field('Starting Price', 'currency', 'Starting performance amount', [], 'fixed'),
                self::field('Performance Duration', 'text', 'Typical performance length'),
                self::field('Artists Included', 'number', 'Number of performers'),
                self::field('Technical Setup Included', 'boolean', 'Are sound and lights included?'),
            ]),
            'Wedding Planner' => array_merge($contact, [
                self::field('Planning Services', 'multi_select', 'Full planning, coordination, guest management'),
                self::field('Starting Planning Fee', 'currency', 'Starting professional fee', [], 'fixed'),
                self::field('Maximum Event Size', 'number', 'Largest guest count normally handled'),
                self::field('Destination Weddings', 'boolean', 'Are destination weddings supported?'),
                self::field('Vendor Coordination Included', 'boolean', 'Will the team coordinate other vendors?'),
            ]),
            'Bridal & Groom Wear' => array_merge($contact, [
                self::field('Product Types', 'multi_select', 'Lehenga, saree, sherwani, suits'),
                self::field('Starting Price', 'currency', 'Starting outfit price', [], 'fixed'),
                self::field('Rental Available', 'boolean', 'Are outfits available on rent?'),
                self::field('Custom Tailoring', 'boolean', 'Is custom tailoring available?'),
                self::field('Delivery Time', 'text', 'Typical order-to-delivery time'),
            ]),
            'Cakes & Desserts' => array_merge($contact, [
                self::field('Specialities', 'multi_select', 'Wedding cakes, cupcakes, dessert tables'),
                self::field('Starting Price Per Kg', 'currency', 'Starting cake price per kg', [], 'per_unit'),
                self::field('Eggless Available', 'boolean', 'Are eggless options available?'),
                self::field('Custom Design Available', 'boolean', 'Can customers request custom designs?'),
                self::field('Delivery Available', 'boolean', 'Is venue delivery available?'),
            ]),
            'Other Services' => array_merge($contact, [
                self::field('Services Offered', 'multi_select', 'List the main services'),
                self::field('Starting Price', 'currency', 'Starting service price', [], 'fixed'),
                self::field('Experience', 'text', 'Example: 8 years'),
                self::field('What Is Included', 'textarea', 'Describe what customers receive'),
            ]),
        ];
    }

    private static function field(string $label, string $type, string $help, array $options = [], string $unit = 'fixed'): array
    {
        return compact('label', 'type', 'help', 'options', 'unit');
    }
}
