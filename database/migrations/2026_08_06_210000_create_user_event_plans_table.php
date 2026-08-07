<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_event_plans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_plan_id')->nullable()->constrained('user_event_plans')->nullOnDelete();
            $table->string('title');
            $table->string('category')->default('wedding');
            $table->unsignedInteger('guest_count');
            $table->json('answers');
            $table->longText('requirement_prompt');
            $table->json('vendor_snapshot')->nullable();
            $table->json('summary')->nullable();
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->string('model')->nullable();
            $table->string('status')->default('generating')->index();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_event_plans');
    }
};
