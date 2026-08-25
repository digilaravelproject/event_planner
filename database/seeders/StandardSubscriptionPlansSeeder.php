<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class StandardSubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        $legacyPlans = [
            'Basic Plan' => ['name' => '3-monthly', 'interval' => 'three_months'],
            'Premium Plan' => ['name' => '6-monthly', 'interval' => 'six_months'],
            'Business Plan' => ['name' => 'yearly', 'interval' => 'yearly'],
            'Free Plan' => ['name' => 'free', 'interval' => 'free'],
            '3 Monthly Plan' => ['name' => '3-monthly', 'interval' => 'three_months'],
            '6 Monthly Plan' => ['name' => '6-monthly', 'interval' => 'six_months'],
            'Yearly Plan' => ['name' => 'yearly', 'interval' => 'yearly'],
        ];

        foreach ($legacyPlans as $legacyName => $attributes) {
            Subscription::where('name', $legacyName)->update($attributes);
        }

        foreach ($this->plans() as $plan) {
            Subscription::updateOrCreate(['interval' => $plan['interval']], $plan);
        }
    }

    private function plans(): array
    {
        return [
            ['name' => 'free', 'price' => 0, 'interval' => 'free', 'features' => ['Profile Access', 'Basic Event Planning', 'Query Support', 'Secure Account']],
            ['name' => '3-monthly', 'price' => 2999, 'interval' => 'three_months', 'features' => ['Full Event Planning', 'Vendor Recommendations', 'Plan Downloads', 'Priority Query Support']],
            ['name' => '6-monthly', 'price' => 4999, 'interval' => 'six_months', 'features' => ['Full Event Planning', 'Vendor Recommendations', 'Plan Downloads', 'Priority Query Support', 'Extended Access']],
            ['name' => 'yearly', 'price' => 12499, 'interval' => 'yearly', 'features' => ['All Planner Features', 'Vendor Recommendations', 'Unlimited Plan Downloads', 'Priority Support', 'One Year Access']],
        ];
    }
}
