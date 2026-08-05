<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('quote_requests');
        Schema::dropIfExists('vendor_registries');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('vendors');
    }

    public function down(): void
    {
        // This migration intentionally removes obsolete data and is not reversible.
    }
};
