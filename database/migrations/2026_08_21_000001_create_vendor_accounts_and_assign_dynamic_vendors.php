<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('business_name');
            $table->string('email')->unique();
            $table->string('phone', 30);
            $table->string('category')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('vendors_dynamic', function (Blueprint $table): void {
            $table->foreignId('vendor_account_id')
                ->nullable()
                ->after('status')
                ->constrained('vendor_accounts')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vendors_dynamic', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('vendor_account_id');
        });
        Schema::dropIfExists('vendor_accounts');
    }
};
