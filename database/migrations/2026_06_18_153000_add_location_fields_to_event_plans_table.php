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
        Schema::table('event_plans', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->after('location')->constrained('states')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained('cities')->onDelete('set null');
            $table->foreignId('area_id')->nullable()->after('city_id')->constrained('areas')->onDelete('set null');
            $table->foreignId('subarea_id')->nullable()->after('area_id')->constrained('subareas')->onDelete('set null');
            $table->json('dynamic_selections')->nullable()->after('entertainment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_plans', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['area_id']);
            $table->dropForeign(['subarea_id']);
            $table->dropColumn(['state_id', 'city_id', 'area_id', 'subarea_id', 'dynamic_selections']);
        });
    }
};
