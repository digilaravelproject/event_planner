<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_requirement_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('question_code')->unique();
            $table->string('question_type');
            $table->string('placeholder')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('status')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('event_requirement_questions'); }
};
