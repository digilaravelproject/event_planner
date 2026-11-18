<?php

use App\Modules\DynamicVendors\Support\VendorCategoryFieldCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description', 500)->nullable();
            $table->json('fields')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        foreach (VendorCategoryFieldCatalog::defaults() as $position => $fields) {
            $name = (string) $position;
            DB::table('vendor_categories')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Vendor form for {$name} services.",
                'fields' => json_encode($fields, JSON_THROW_ON_ERROR),
                'is_active' => true,
                'sort_order' => array_search($name, array_keys(VendorCategoryFieldCatalog::defaults()), true),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_categories');
    }
};
