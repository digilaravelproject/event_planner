<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('subscriptions')->where('price', 0)->update(['interval' => 'free']);
        DB::table('subscriptions')->where('price', '>', 0)->where('interval', 'monthly')->update(['interval' => 'three_months']);
        DB::table('subscriptions')->where('interval', 'lifetime')->update(['interval' => 'yearly']);
    }

    public function down(): void
    {
        DB::table('subscriptions')->where('interval', 'free')->update(['interval' => 'monthly']);
        DB::table('subscriptions')->where('interval', 'three_months')->update(['interval' => 'monthly']);
    }
};
