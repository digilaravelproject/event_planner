<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_requirement_questions', function (Blueprint $table): void {
            $table->json('vendor_attribute_images')->nullable()->after('vendor_attribute_values');
        });
    }

    public function down(): void
    {
        Schema::table('event_requirement_questions', function (Blueprint $table): void {
            $table->dropColumn('vendor_attribute_images');
        });
    }
};
