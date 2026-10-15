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
        Schema::create('vendor_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('contact_no', 50);
            $table->string('email');
            $table->string('location');
            $table->string('vendor_category');
            $table->text('service');
            $table->string('capacity')->nullable(); // For banquets/venues (e.g., 200 - 1500 guests)
            $table->string('price')->nullable(); // Starting price or price range
            $table->text('packages')->nullable(); // Details of packages offered
            $table->json('media_files')->nullable(); // Uploaded photos/videos paths or links
            $table->string('owner_name');
            $table->string('owner_number', 50);
            $table->string('owner_email');
            $table->string('status')->default('pending'); // pending, contacted, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_enquiries');
    }
};
