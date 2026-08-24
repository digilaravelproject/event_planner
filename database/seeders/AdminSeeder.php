<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
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

        // 2. Seed Subscriptions
        $plans = [
            [
                'name' => 'Free Plan',
                'price' => 0,
                'interval' => 'free',
                'features' => ['Profile Access', 'Email Support', 'Notification Updates', 'Query Support', 'Secure Account'],
            ],
            [
                'name' => '3 Monthly Plan',
                'price' => 2999.00,
                'interval' => 'three_months',
                'features' => ['Profile Access', 'Priority Support', 'Notification Updates', 'Query Support', 'Team Members (Up to 5)'],
            ],
            [
                'name' => '6 Monthly Plan',
                'price' => 4999.00,
                'interval' => 'six_months',
                'features' => ['Enterprise Account', '24/7 Phone Support', 'Advanced Analytics Reports', 'SLA Guarantee', 'Dedicated Support', 'API Integration Access'],
            ],
            [
                'name' => 'Yearly Plan',
                'price' => 8999.00,
                'interval' => 'yearly',
                'features' => ['All planner tools', 'Priority Support', 'Advanced Analytics Reports', 'Unlimited Query Support', 'Exportable Plans'],
            ],
        ];

        foreach ($plans as $p) {
            Subscription::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
