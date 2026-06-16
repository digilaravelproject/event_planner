<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_masters', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // e.g. event_types, budget_ranges, guest_ranges, cities, food_types, venue_types, styles, entertainment_types
            $table->string('label');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_masters');
    }
};
