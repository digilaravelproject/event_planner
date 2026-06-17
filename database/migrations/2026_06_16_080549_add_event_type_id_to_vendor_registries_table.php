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
        Schema::table('vendor_registries', function (Blueprint $table) {
            $table->unsignedBigInteger('event_type_id')->nullable()->after('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_registries', function (Blueprint $table) {
            $table->dropColumn('event_type_id');
        });
    }
};
