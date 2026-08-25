<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing plans
        DB::table('subscriptions')->where('interval', 'free')->update(['name' => 'free', 'price' => 0.00]);
        DB::table('subscriptions')->where('interval', 'three_months')->update(['name' => '3-monthly', 'price' => 2999.00]);
        DB::table('subscriptions')->where('interval', 'six_months')->update(['name' => '6-monthly', 'price' => 4999.00]);
        DB::table('subscriptions')->where('interval', 'yearly')->update(['name' => 'yearly', 'price' => 12499.00]);

        // Insert them if they do not exist
        $plans = [
            [
                'name' => 'free',
                'price' => 0.00,
                'interval' => 'free',
                'features' => json_encode(['Profile Access', 'Basic Event Planning', 'Query Support', 'Secure Account']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '3-monthly',
                'price' => 2999.00,
                'interval' => 'three_months',
                'features' => json_encode(['Full Event Planning', 'Vendor Recommendations', 'Plan Downloads', 'Priority Query Support']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '6-monthly',
                'price' => 4999.00,
                'interval' => 'six_months',
                'features' => json_encode(['Full Event Planning', 'Vendor Recommendations', 'Plan Downloads', 'Priority Query Support', 'Extended Access']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'yearly',
                'price' => 12499.00,
                'interval' => 'yearly',
                'features' => json_encode(['All Planner Features', 'Vendor Recommendations', 'Unlimited Plan Downloads', 'Priority Support', 'One Year Access']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $plan) {
            $exists = DB::table('subscriptions')->where('interval', $plan['interval'])->exists();
            if (!$exists) {
                DB::table('subscriptions')->insert($plan);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('subscriptions')->where('interval', 'free')->update(['name' => 'Free Plan', 'price' => 0.00]);
        DB::table('subscriptions')->where('interval', 'three_months')->update(['name' => '3 Monthly Plan', 'price' => 2999.00]);
        DB::table('subscriptions')->where('interval', 'six_months')->update(['name' => '6 Monthly Plan', 'price' => 4999.00]);
        DB::table('subscriptions')->where('interval', 'yearly')->update(['name' => 'Yearly Plan', 'price' => 12499.00]);
    }
};
