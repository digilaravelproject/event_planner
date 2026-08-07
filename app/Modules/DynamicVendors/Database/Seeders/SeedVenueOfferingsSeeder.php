<?php

namespace App\Modules\DynamicVendors\Database\Seeders;

use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Database\Seeder;

class SeedVenueOfferingsSeeder extends Seeder
{
    public function run(): void
    {
        $customData = [
            [
                "vendor_name" => "Bliss Decor Studio",
                "offerings" => [
                    [
                        "name" => "Ocean Pearl Sunset Mandap",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 50,
                        "max_capacity" => 450,
                        "min_budget" => 6,
                        "max_budget" => 24,
                        "locations" => ["Juhu / Bandra Sea-Face", "South Mumbai Heritage"],
                        "traditions" => ["Marwari / Rajputana Royal", "North Indian Punjabi"],
                        "notes" => "Premium beachfront floral mandap with sunset backdrop, welcome arch, aisle decor and VIP seating."
                    ],
                    [
                        "name" => "Moonlight Pool Pavilion",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 120,
                        "max_capacity" => 700,
                        "min_budget" => 10,
                        "max_budget" => 34,
                        "locations" => ["Suburban AC Banquets", "Thane & Navi Mumbai"],
                        "traditions" => ["South Indian Tradition", "Gujarati Garba Shaadi"],
                        "notes" => "Poolside mandap with floating candles, fairy lighting and luxury lounge setup."
                    ],
                    [
                        "name" => "Crystal Harmony Ballroom",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 250,
                        "max_capacity" => 1400,
                        "min_budget" => 18,
                        "max_budget" => 58,
                        "locations" => ["All Mumbai"],
                        "traditions" => ["Muslim Nikah & Walima", "Catholic / Christian Wedding"],
                        "notes" => "Elegant ballroom styling with crystal chandeliers, premium stage and luxury guest seating."
                    ],
                    [
                        "name" => "Royal Heritage Courtyard",
                        "category" => "Heritage Resort",
                        "min_capacity" => 150,
                        "max_capacity" => 900,
                        "min_budget" => 14,
                        "max_budget" => 45,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Maharashtrian Lagna", "Marwari / Rajputana Royal"],
                        "notes" => "Palace-inspired heritage venue decor with vintage entrance and royal floral installations."
                    ]
                ]
            ],
            [
                "vendor_name" => "Elite Decor Studio",
                "offerings" => [
                    [
                        "name" => "Golden Coast Wedding Deck",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 60,
                        "max_capacity" => 500,
                        "min_budget" => 7,
                        "max_budget" => 25,
                        "locations" => ["Juhu / Bandra Sea-Face", "All Mumbai"],
                        "traditions" => ["Gujarati Garba Shaadi", "North Indian Punjabi"],
                        "notes" => "Sea-view mandap with luxury floral tunnel, LED pathway and elegant reception lounge."
                    ],
                    [
                        "name" => "Emerald Garden Celebration",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 100,
                        "max_capacity" => 800,
                        "min_budget" => 12,
                        "max_budget" => 36,
                        "locations" => ["Suburban AC Banquets", "Thane & Navi Mumbai"],
                        "traditions" => ["South Indian Tradition", "Maharashtrian Lagna"],
                        "notes" => "Garden venue with illuminated trees, floral gazebo and designer dining arrangements."
                    ],
                    [
                        "name" => "Imperial Sapphire Hall",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 220,
                        "max_capacity" => 1500,
                        "min_budget" => 20,
                        "max_budget" => 60,
                        "locations" => ["Suburban AC Banquets", "All Mumbai"],
                        "traditions" => ["Catholic / Christian Wedding", "Muslim Nikah & Walima"],
                        "notes" => "Luxury indoor ballroom with premium stage backdrop, chandelier ceiling and VIP lounge."
                    ],
                    [
                        "name" => "Vintage Palace Retreat",
                        "category" => "Heritage Resort",
                        "min_capacity" => 180,
                        "max_capacity" => 950,
                        "min_budget" => 15,
                        "max_budget" => 48,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Marwari / Rajputana Royal", "Maharashtrian Lagna"],
                        "notes" => "Classic palace-inspired decor with royal arches, traditional entrance and heritage courtyard."
                    ]
                ]
            ],
            [
                "vendor_name" => "Royal Decor Studio",
                "offerings" => [
                    [
                        "name" => "Coral Breeze Wedding Cove",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 70,
                        "max_capacity" => 480,
                        "min_budget" => 6,
                        "max_budget" => 23,
                        "locations" => ["Juhu / Bandra Sea-Face", "All Mumbai"],
                        "traditions" => ["North Indian Punjabi", "Muslim Nikah & Walima"],
                        "notes" => "Oceanfront ceremony setup with floral aisle, shell-inspired mandap and premium guest lounge."
                    ],
                    [
                        "name" => "Twilight Garden Fiesta",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 140,
                        "max_capacity" => 850,
                        "min_budget" => 11,
                        "max_budget" => 37,
                        "locations" => ["Suburban AC Banquets", "Thane & Navi Mumbai"],
                        "traditions" => ["Gujarati Garba Shaadi", "South Indian Tradition"],
                        "notes" => "Open lawn celebration with hanging lanterns, designer cabanas and illuminated stage backdrop."
                    ],
                    [
                        "name" => "Diamond Luxe Convention Hall",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 250,
                        "max_capacity" => 1450,
                        "min_budget" => 19,
                        "max_budget" => 59,
                        "locations" => ["Suburban AC Banquets", "All Mumbai"],
                        "traditions" => ["Catholic / Christian Wedding", "Marwari / Rajputana Royal"],
                        "notes" => "Grand ballroom with mirrored stage, luxury ceiling lighting and premium reception seating."
                    ],
                    [
                        "name" => "Kings Manor Heritage Estate",
                        "category" => "Heritage Resort",
                        "min_capacity" => 170,
                        "max_capacity" => 920,
                        "min_budget" => 16,
                        "max_budget" => 47,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Maharashtrian Lagna", "Marwari / Rajputana Royal"],
                        "notes" => "Royal heritage venue featuring palace gates, vintage décor and traditional courtyard ambience."
                    ]
                ]
            ],
            [
                "vendor_name" => "Urban Decor Studio",
                "offerings" => [
                    [
                        "name" => "Azure Wave Celebration Point",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 80,
                        "max_capacity" => 520,
                        "min_budget" => 7,
                        "max_budget" => 26,
                        "locations" => ["Juhu / Bandra Sea-Face", "South Mumbai Heritage"],
                        "traditions" => ["Gujarati Garba Shaadi", "Catholic / Christian Wedding"],
                        "notes" => "Modern seaside décor with luxury floral pillars, ocean-view stage and designer lounge seating."
                    ],
                    [
                        "name" => "Skyline Pool Garden Venue",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 110,
                        "max_capacity" => 780,
                        "min_budget" => 10,
                        "max_budget" => 35,
                        "locations" => ["Thane & Navi Mumbai", "Suburban AC Banquets"],
                        "traditions" => ["South Indian Tradition", "North Indian Punjabi"],
                        "notes" => "Elegant lawn venue with floating pool décor, LED trees and premium dining arrangements."
                    ],
                    [
                        "name" => "Opal Prestige Ballroom",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 230,
                        "max_capacity" => 1500,
                        "min_budget" => 21,
                        "max_budget" => 60,
                        "locations" => ["All Mumbai", "Suburban AC Banquets"],
                        "traditions" => ["Muslim Nikah & Walima", "Catholic / Christian Wedding"],
                        "notes" => "Luxury ballroom with crystal chandeliers, designer stage backdrop and exclusive VIP lounge."
                    ],
                    [
                        "name" => "Regal Fort Heritage Gardens",
                        "category" => "Heritage Resort",
                        "min_capacity" => 200,
                        "max_capacity" => 980,
                        "min_budget" => 17,
                        "max_budget" => 49,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Marwari / Rajputana Royal", "Maharashtrian Lagna"],
                        "notes" => "Historic-style venue with majestic entrance, palace courtyard décor and heritage lighting concepts."
                    ]
                ]
            ],
            [
                "vendor_name" => "Grand Celebration Lawns",
                "offerings" => [
                    [
                        "name" => "Royal Sunset Ocean Pavilion",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 90,
                        "max_capacity" => 600,
                        "min_budget" => 8,
                        "max_budget" => 28,
                        "locations" => ["Juhu / Bandra Sea-Face", "All Mumbai"],
                        "traditions" => ["Marwari / Rajputana Royal", "Gujarati Garba Shaadi"],
                        "notes" => "Luxury seaside wedding setup with grand floral mandap, ocean-facing stage and premium guest seating."
                    ],
                    [
                        "name" => "Grand Mirage Pool Lawn",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 150,
                        "max_capacity" => 1000,
                        "min_budget" => 14,
                        "max_budget" => 42,
                        "locations" => ["Thane & Navi Mumbai", "Suburban AC Banquets"],
                        "traditions" => ["North Indian Punjabi", "South Indian Tradition"],
                        "notes" => "Large open-air lawn with poolside décor, luxury lighting canopy and themed celebration zones."
                    ],
                    [
                        "name" => "Majestic Crown Ballroom",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 300,
                        "max_capacity" => 1800,
                        "min_budget" => 22,
                        "max_budget" => 70,
                        "locations" => ["All Mumbai", "Suburban AC Banquets"],
                        "traditions" => ["Muslim Nikah & Walima", "Catholic / Christian Wedding"],
                        "notes" => "Premium banquet experience with royal stage design, chandelier décor and spacious dining setup."
                    ],
                    [
                        "name" => "Heritage Palace Garden Retreat",
                        "category" => "Heritage Resort",
                        "min_capacity" => 220,
                        "max_capacity" => 1200,
                        "min_budget" => 18,
                        "max_budget" => 55,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Maharashtrian Lagna", "Marwari / Rajputana Royal"],
                        "notes" => "Heritage-inspired outdoor venue with traditional architecture, royal pathways and vintage décor."
                    ]
                ]
            ],
            [
                "vendor_name" => "Emerald Celebration Lawns",
                "offerings" => [
                    [
                        "name" => "Emerald Coastal Dream Terrace",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 75,
                        "max_capacity" => 550,
                        "min_budget" => 7,
                        "max_budget" => 27,
                        "locations" => ["Juhu / Bandra Sea-Face", "South Mumbai Heritage"],
                        "traditions" => ["North Indian Punjabi", "Maharashtrian Lagna"],
                        "notes" => "Elegant sea-view wedding terrace featuring floral décor, sunset theme and designer entrance."
                    ],
                    [
                        "name" => "Green Haven Floating Garden",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 130,
                        "max_capacity" => 900,
                        "min_budget" => 12,
                        "max_budget" => 40,
                        "locations" => ["Thane & Navi Mumbai", "All Mumbai"],
                        "traditions" => ["Gujarati Garba Shaadi", "South Indian Tradition"],
                        "notes" => "Nature-inspired lawn setup with floating pool elements, floral pathways and ambient lighting."
                    ],
                    [
                        "name" => "Emerald Grand Crystal Arena",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 280,
                        "max_capacity" => 1600,
                        "min_budget" => 20,
                        "max_budget" => 65,
                        "locations" => ["Suburban AC Banquets", "All Mumbai"],
                        "traditions" => ["Muslim Nikah & Walima", "Catholic / Christian Wedding"],
                        "notes" => "Modern luxury ballroom with crystal theme décor, elevated stage and premium banquet facilities."
                    ],
                    [
                        "name" => "Emerald Royal Courtyard Resort",
                        "category" => "Heritage Resort",
                        "min_capacity" => 190,
                        "max_capacity" => 1100,
                        "min_budget" => 16,
                        "max_budget" => 52,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Marwari / Rajputana Royal", "Maharashtrian Lagna"],
                        "notes" => "Royal courtyard venue with heritage pillars, traditional décor elements and luxury wedding ambience."
                    ]
                ]
            ],
            [
                "vendor_name" => "Signature Celebration Lawns",
                "offerings" => [
                    [
                        "name" => "Signature Ocean Royale Setup",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 100,
                        "max_capacity" => 650,
                        "min_budget" => 9,
                        "max_budget" => 30,
                        "locations" => ["Juhu / Bandra Sea-Face", "All Mumbai"],
                        "traditions" => ["Marwari / Rajputana Royal", "North Indian Punjabi"],
                        "notes" => "Premium beachfront celebration with designer floral mandap, ocean view stage and luxury seating arrangements."
                    ],
                    [
                        "name" => "Signature Twilight Aqua Lawn",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 180,
                        "max_capacity" => 1100,
                        "min_budget" => 15,
                        "max_budget" => 45,
                        "locations" => ["Thane & Navi Mumbai", "Suburban AC Banquets"],
                        "traditions" => ["Gujarati Garba Shaadi", "South Indian Tradition"],
                        "notes" => "Large poolside lawn featuring floating décor, fairy light canopy and luxury guest experience zones."
                    ],
                    [
                        "name" => "Signature Imperial Grand Hall",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 350,
                        "max_capacity" => 2000,
                        "min_budget" => 25,
                        "max_budget" => 75,
                        "locations" => ["All Mumbai", "Suburban AC Banquets"],
                        "traditions" => ["Muslim Nikah & Walima", "Catholic / Christian Wedding"],
                        "notes" => "Ultra-premium ballroom with grand stage production, chandelier lighting and luxury banquet styling."
                    ],
                    [
                        "name" => "Signature Heritage Palace Lawn",
                        "category" => "Heritage Resort",
                        "min_capacity" => 250,
                        "max_capacity" => 1300,
                        "min_budget" => 20,
                        "max_budget" => 60,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Maharashtrian Lagna", "Marwari / Rajputana Royal"],
                        "notes" => "Royal heritage lawn featuring palace-inspired décor, vintage architecture and traditional wedding themes."
                    ]
                ]
            ],
            [
                "vendor_name" => "Shree Celebration Lawns",
                "offerings" => [
                    [
                        "name" => "Shree Coastal Paradise Mandap",
                        "category" => "Sea-Facing Beachfront",
                        "min_capacity" => 60,
                        "max_capacity" => 500,
                        "min_budget" => 6,
                        "max_budget" => 26,
                        "locations" => ["Juhu / Bandra Sea-Face", "South Mumbai Heritage"],
                        "traditions" => ["Maharashtrian Lagna", "Gujarati Garba Shaadi"],
                        "notes" => "Beautiful coastal wedding setup with traditional mandap, floral decoration and sea-facing ambience."
                    ],
                    [
                        "name" => "Shree Blossom Pool Garden",
                        "category" => "Lawn & Poolside",
                        "min_capacity" => 120,
                        "max_capacity" => 850,
                        "min_budget" => 11,
                        "max_budget" => 38,
                        "locations" => ["Thane & Navi Mumbai", "All Mumbai"],
                        "traditions" => ["South Indian Tradition", "North Indian Punjabi"],
                        "notes" => "Elegant garden venue with floral installations, poolside seating and evening illumination."
                    ],
                    [
                        "name" => "Shree Elite Celebration Arena",
                        "category" => "Grand AC Ballroom",
                        "min_capacity" => 260,
                        "max_capacity" => 1700,
                        "min_budget" => 21,
                        "max_budget" => 68,
                        "locations" => ["Suburban AC Banquets", "All Mumbai"],
                        "traditions" => ["Muslim Nikah & Walima", "Catholic / Christian Wedding"],
                        "notes" => "Luxury indoor wedding arena with premium stage décor, modern lighting and spacious guest facilities."
                    ],
                    [
                        "name" => "Shree Heritage Royal Retreat",
                        "category" => "Heritage Resort",
                        "min_capacity" => 200,
                        "max_capacity" => 1150,
                        "min_budget" => 17,
                        "max_budget" => 54,
                        "locations" => ["South Mumbai Heritage", "All Mumbai"],
                        "traditions" => ["Marwari / Rajputana Royal", "Maharashtrian Lagna"],
                        "notes" => "Traditional royal retreat with heritage charm, grand entrance décor and cultural wedding styling."
                    ]
                ]
            ]
        ];

        $allVendors = DynamicVendor::all();

        // Wipe old offerings
        foreach ($allVendors as $vendor) {
            $json = $vendor->vendor_json ?? [];
            $json['offerings'] = [];
            $vendor->vendor_json = $json;
            $vendor->save();
        }

        foreach ($customData as $vendorData) {
            $targetName = strtolower(trim($vendorData['vendor_name']));
            
            $vendor = $allVendors->first(function ($v) use ($targetName) {
                $name = strtolower(trim(data_get($v->vendor_json, 'identity.name') ?: data_get($v->vendor_json, 'name') ?: ''));
                return $name === $targetName || str_contains($name, $targetName) || str_contains($targetName, strtok($name, ' '));
            });

            if ($vendor) {
                $json = $vendor->vendor_json ?? [];
                $json['identity']['name'] = $vendorData['vendor_name'];
                $json['name'] = $vendorData['vendor_name'];
                $json['offerings'] = $vendorData['offerings'];
                $vendor->vendor_json = $json;
                $vendor->save();
            } else {
                // Create vendor if not exists
                $newVendor = new DynamicVendor();
                $newVendor->status = 'active';
                $newVendor->vendor_json = [
                    'identity' => [
                        'name' => $vendorData['vendor_name'],
                        'category' => 'Decorator'
                    ],
                    'name' => $vendorData['vendor_name'],
                    'category' => 'Decorator',
                    'offerings' => $vendorData['offerings']
                ];
                $newVendor->save();
            }
        }
    }
}
