<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_requirement_questions', function (Blueprint $table): void {
            $table->json('option_images')->nullable()->after('options');
            $table->json('option_vendor_values')->nullable()->after('option_images');
        });
    }

    public function down(): void
    {
        Schema::table('event_requirement_questions', function (Blueprint $table): void {
            $table->dropColumn(['option_images', 'option_vendor_values']);
        });
    }
};
