<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
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

        $this->call(StandardSubscriptionPlansSeeder::class);
    }
}
