<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_module_options', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index();
            $table->string('value');
            $table->string('label');
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['group', 'value']);
        });
    }

    public function down(): void { Schema::dropIfExists('admin_module_options'); }
};
