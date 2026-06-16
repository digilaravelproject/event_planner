<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\SystemMaster;
use App\Models\Vendor;
use App\Models\Venue;
use App\Models\BudgetRule;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Admin
        Admin::updateOrCreate(
            ['email' => 'admin@eventplanner.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Seed System Masters
        $masters = [
            'event_types' => ['Wedding', 'Birthday Party', 'Corporate Event', 'Anniversary', 'Baby Shower', 'Conference'],
            'budget_ranges' => ['Under ₹5 Lakhs', '₹5 Lakhs - ₹10 Lakhs', '₹10 Lakhs - ₹25 Lakhs', '₹25 Lakhs - ₹50 Lakhs', 'Above ₹50 Lakhs'],
            'guest_ranges' => ['Under 100 guests', '100 - 250 guests', '250 - 500 guests', '500 - 1000 guests', '1000+ guests'],
            'cities' => ['Mumbai', 'Delhi', 'Bangalore', 'Pune', 'Goa', 'Jaipur', 'Hyderabad'],
            'food_types' => ['Veg Only', 'Non-Veg Only', 'Multi-Cuisine', 'Italian', 'Continental', 'Traditional Indian'],
            'venue_types' => ['Banquet Hall', 'Lawn/Garden', 'Beach Resort', '5-Star Hotel', 'Heritage Palace', 'Rooftop Lounge'],
            'styles' => ['Luxury', 'Classic', 'Modern', 'Rustic', 'Bohemian', 'Traditional'],
            'entertainment_types' => ['DJ Only', 'Live Band', 'Classical Singer', 'Traditional Dhol', 'Celebrity Appearance', 'None']
        ];

        foreach ($masters as $type => $labels) {
            foreach ($labels as $label) {
                SystemMaster::updateOrCreate([
                    'type' => $type,
                    'label' => $label
                ]);
            }
        }

        // 3. Seed Vendors
        $vendorsData = [
            [
                'name' => 'Elite Catering Service',
                'email' => 'catering@elite.com',
                'phone' => '+91 98765 43210',
                'business_name' => 'Elite Gastronomy',
                'category' => 'Catering',
                'city' => 'Mumbai',
                'status' => true,
                'description' => 'Gourmet catering services specializing in Indian traditional and international cuisines.',
                'base_price' => 120000.00,
                'rating' => 4.80,
            ],
            [
                'name' => 'Royal Flower Decorators',
                'email' => 'decor@royalflowers.com',
                'phone' => '+91 98222 11111',
                'business_name' => 'Royal Floral & Events',
                'category' => 'Decor',
                'city' => 'Goa',
                'status' => true,
                'description' => 'Premium floral installations, theme design, and lighting for destination weddings.',
                'base_price' => 350000.00,
                'rating' => 4.90,
            ],
            [
                'name' => 'Vibe DJ & Lights',
                'email' => 'vibe@djservices.com',
                'phone' => '+91 95432 10987',
                'business_name' => 'Vibe Entertainment Systems',
                'category' => 'Entertainment',
                'city' => 'Bangalore',
                'status' => true,
                'description' => 'Club DJs, acoustic bands, and professional sound systems with premium intelligent lighting.',
                'base_price' => 75000.00,
                'rating' => 4.60,
            ],
            [
                'name' => 'Snapshot Studio',
                'email' => 'shoot@snapshot.com',
                'phone' => '+91 98989 89898',
                'business_name' => 'Snapshot Wedding Films',
                'category' => 'Photography',
                'city' => 'Delhi',
                'status' => true,
                'description' => 'Cinematic cinematography and documentary style photography capturing the candid moments.',
                'base_price' => 150000.00,
                'rating' => 4.70,
            ],
            [
                'name' => 'The Grand Feast Co.',
                'email' => 'feast@grandfeast.com',
                'phone' => '+91 97777 66666',
                'business_name' => 'Grand Feast Caterers',
                'category' => 'Catering',
                'city' => 'Delhi',
                'status' => false,
                'description' => 'Affordable and rich multi-cuisine buffet for mid to large size events.',
                'base_price' => 85000.00,
                'rating' => 4.30,
            ],
            [
                'name' => 'Acoustic Harmonics Band',
                'email' => 'acoustic@harmonics.com',
                'phone' => '+91 96666 55555',
                'business_name' => 'Harmonics Band & Singers',
                'category' => 'Entertainment',
                'city' => 'Pune',
                'status' => true,
                'description' => 'Five-piece live band doing fusion, classic rock, and popular Bollywood instrumentals.',
                'base_price' => 95000.00,
                'rating' => 4.50,
            ]
        ];

        foreach ($vendorsData as $v) {
            Vendor::updateOrCreate(['email' => $v['email']], $v);
        }

        // 4. Seed Venues
        $venuesData = [
            [
                'name' => 'Taj Palace Gardens',
                'city' => 'Mumbai',
                'capacity' => 800,
                'price_per_day' => 450000.00,
                'status' => true,
            ],
            [
                'name' => 'Blue Wave Beach Resort',
                'city' => 'Goa',
                'capacity' => 350,
                'price_per_day' => 320000.00,
                'status' => true,
            ],
            [
                'name' => 'Elysian Imperial Banquet',
                'city' => 'Delhi',
                'capacity' => 1200,
                'price_per_day' => 600000.00,
                'status' => true,
            ],
            [
                'name' => 'Hillside Green Lawn',
                'city' => 'Pune',
                'capacity' => 500,
                'price_per_day' => 180000.00,
                'status' => true,
            ]
        ];

        foreach ($venuesData as $vn) {
            Venue::updateOrCreate(['name' => $vn['name']], $vn);
        }

        // 5. Seed Budget Rules
        BudgetRule::updateOrCreate(
            ['event_type' => 'Wedding', 'style_aesthetic' => 'Luxury'],
            [
                'shares' => [
                    'Venue' => 45,
                    'Food' => 34,
                    'Decor' => 20,
                    'Photo' => 10,
                    'Entertainment' => 5,
                    'Misc' => 10,
                ]
            ]
        );

        BudgetRule::updateOrCreate(
            ['event_type' => 'Corporate Event', 'style_aesthetic' => 'Modern'],
            [
                'shares' => [
                    'Venue' => 30,
                    'Food' => 40,
                    'Decor' => 10,
                    'Photo' => 10,
                    'Entertainment' => 5,
                    'Misc' => 5,
                ]
            ]
        );

        // 6. Seed Subscriptions
        $plans = [
            [
                'name' => 'Starter Plan',
                'price' => 1999.00,
                'interval' => 'monthly',
                'features' => ['10 Active Events', '50 Registered Guests', 'Email Support', 'Basic Rule Engine Templates', 'Responsive Event Forms'],
            ],
            [
                'name' => 'Professional Plan',
                'price' => 4999.00,
                'interval' => 'monthly',
                'features' => ['Unlimited Events', '500 Guests per Event', 'Priority Support', 'Full Budget Rule Engine Access', 'Custom White-labeling', 'Team Members (Up to 5)'],
            ],
            [
                'name' => 'Enterprise Plan',
                'price' => 9999.00,
                'interval' => 'monthly',
                'features' => ['Unlimited Events', 'Unlimited Guests', '24/7 Phone Support', 'Advanced Analytics Reports', 'SLA Guarantee', 'Dedicated Event Architect', 'API Integration Access'],
            ]
        ];

        foreach ($plans as $p) {
            Subscription::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
