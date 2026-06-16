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
        // 1. Create states table
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 2. Create cities table
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->unique(['state_id', 'name']);
        });

        // 3. Create areas table
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->unique(['city_id', 'name']);
        });

        // 4. Create subareas table
        Schema::create('subareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->unique(['area_id', 'name']);
        });

        // 5. Update vendors table
        Schema::table('vendors', function (Blueprint $table) {
            $table->rememberToken()->after('password');
            $table->foreignId('state_id')->nullable()->after('city')->constrained('states')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained('cities')->onDelete('set null');
            $table->foreignId('area_id')->nullable()->after('city_id')->constrained('areas')->onDelete('set null');
            $table->foreignId('subarea_id')->nullable()->after('area_id')->constrained('subareas')->onDelete('set null');
        });

        // 6. Update venues table
        Schema::table('venues', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->after('city')->constrained('states')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained('cities')->onDelete('set null');
            $table->foreignId('area_id')->nullable()->after('city_id')->constrained('areas')->onDelete('set null');
            $table->foreignId('subarea_id')->nullable()->after('area_id')->constrained('subareas')->onDelete('set null');
        });

        // 7. Seed Maharashtra, Mumbai, Areas & Subareas
        $stateId = DB::table('states')->insertGetId([
            'name' => 'Maharashtra',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cityId = DB::table('cities')->insertGetId([
            'state_id' => $stateId,
            'name' => 'Mumbai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $areas = [
            'Andheri' => ['Andheri West', 'Andheri East', 'Lokhandwala', 'Versova'],
            'Bandra' => ['Bandra West', 'Bandra East', 'Carter Road', 'Pali Hill'],
            'Colaba' => ['Cuffe Parade', 'Colaba Causeway', 'Navy Nagar'],
            'Borivali' => ['Borivali West', 'Borivali East', 'IC Colony'],
            'Dadar' => ['Dadar West', 'Dadar East', 'Shivaji Park'],
        ];

        foreach ($areas as $areaName => $subareaNames) {
            $areaId = DB::table('areas')->insertGetId([
                'city_id' => $cityId,
                'name' => $areaName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($subareaNames as $subName) {
                DB::table('subareas')->insert([
                    'area_id' => $areaId,
                    'name' => $subName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['area_id']);
            $table->dropForeign(['subarea_id']);
            $table->dropColumn(['state_id', 'city_id', 'area_id', 'subarea_id']);
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropRememberToken();
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['area_id']);
            $table->dropForeign(['subarea_id']);
            $table->dropColumn(['state_id', 'city_id', 'area_id', 'subarea_id']);
        });

        Schema::dropIfExists('subareas');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
    }
};
