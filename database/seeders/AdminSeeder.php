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
                'name' => 'Starter Plan',
                'price' => 1999.00,
                'interval' => 'monthly',
                'features' => ['Profile Access', 'Email Support', 'Notification Updates', 'Feedback Access', 'Secure Account'],
            ],
            [
                'name' => 'Professional Plan',
                'price' => 4999.00,
                'interval' => 'monthly',
                'features' => ['Profile Access', 'Priority Support', 'Notification Updates', 'Feedback Access', 'Team Members (Up to 5)'],
            ],
            [
                'name' => 'Enterprise Plan',
                'price' => 9999.00,
                'interval' => 'monthly',
                'features' => ['Enterprise Account', '24/7 Phone Support', 'Advanced Analytics Reports', 'SLA Guarantee', 'Dedicated Support', 'API Integration Access'],
            ]
        ];

        foreach ($plans as $p) {
            Subscription::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
