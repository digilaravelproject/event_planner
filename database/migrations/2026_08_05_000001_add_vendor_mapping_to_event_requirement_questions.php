<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_requirement_questions', function (Blueprint $table): void {
            $table->string('vendor_attribute_key')->nullable()->after('options')->index();
            $table->string('vendor_attribute_label')->nullable()->after('vendor_attribute_key');
            $table->json('vendor_attribute_values')->nullable()->after('vendor_attribute_label');
        });
    }

    public function down(): void
    {
        Schema::table('event_requirement_questions', function (Blueprint $table): void {
            $table->dropIndex(['vendor_attribute_key']);
            $table->dropColumn(['vendor_attribute_key', 'vendor_attribute_label', 'vendor_attribute_values']);
        });
    }
};
