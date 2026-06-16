<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_registries', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->timestamps();
        });

        // Insert initial registries
        $defaultRegistries = [
            ['key' => 'event_types', 'title' => 'Event Types'],
            ['key' => 'budget_ranges', 'title' => 'Budget Ranges'],
            ['key' => 'guest_ranges', 'title' => 'Guest Ranges'],
            ['key' => 'cities', 'title' => 'Cities'],
            ['key' => 'food_types', 'title' => 'Food Types'],
            ['key' => 'venue_types', 'title' => 'Venue Types'],
            ['key' => 'styles', 'title' => 'Styles'],
            ['key' => 'entertainment_types', 'title' => 'Entertainment Types'],
        ];

        foreach ($defaultRegistries as $reg) {
            DB::table('master_registries')->insert(array_merge($reg, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_registries');
    }
};
