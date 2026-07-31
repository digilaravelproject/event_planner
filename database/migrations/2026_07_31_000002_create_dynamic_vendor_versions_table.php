<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dynamic_vendor_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dynamic_vendor_id')->constrained('vendors_dynamic')->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->json('vendor_json');
            $table->string('status', 20);
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();

            $table->unique(['dynamic_vendor_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_vendor_versions');
    }
};
