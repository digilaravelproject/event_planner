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
        Schema::table('vendors', function (Blueprint $table) {
            $table->enum('costing_type', ['percentage', 'rupees'])->default('percentage')->after('description');
        });

        Schema::table('vendor_registries', function (Blueprint $table) {
            $table->decimal('share_rupees', 10, 2)->default(0.00)->after('share_percentage');
            $table->boolean('status')->default(1)->after('share_rupees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_registries', function (Blueprint $table) {
            $table->dropColumn(['share_rupees', 'status']);
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('costing_type');
        });
    }
};
