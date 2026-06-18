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
        Schema::create('event_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('event_type');
            $table->string('budget');
            $table->string('guests');
            $table->string('location');
            $table->date('date');
            $table->string('venue_type');
            $table->string('food_type');
            $table->string('style');
            $table->string('decoration_type');
            $table->string('entertainment_type');
            $table->json('budget_shares')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_plans');
    }
};
