<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->unsignedTinyInteger('rating');
            $table->string('status')->default('pending')->index();
            $table->text('admin_reply')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('feedback'); }
};
