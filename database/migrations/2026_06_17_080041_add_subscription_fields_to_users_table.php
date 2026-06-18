<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_number')->nullable()->after('email');
            $table->foreignId('subscription_id')->nullable()->after('mobile_number')->constrained('subscriptions')->onDelete('set null');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_id');
            $table->string('razorpay_payment_id')->nullable()->after('subscription_ends_at');
            $table->string('razorpay_order_id')->nullable()->after('razorpay_payment_id');
        });

        // Insert decoration_types registry
        DB::table('master_registries')->insertOrIgnore([
            'key' => 'decoration_types',
            'title' => 'Decoration Types',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert default decoration system masters
        $decorations = ['Floral Theme', 'Minimalist Elegance', 'Fairytale Palace', 'Vintage Garden', 'Rustic Barn', 'Bohemian Chic'];
        foreach ($decorations as $decor) {
            DB::table('system_masters')->insertOrIgnore([
                'type' => 'decoration_types',
                'label' => $decor,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropColumn(['mobile_number', 'subscription_id', 'subscription_ends_at', 'razorpay_payment_id', 'razorpay_order_id']);
        });

        DB::table('system_masters')->where('type', 'decoration_types')->delete();
        DB::table('master_registries')->where('key', 'decoration_types')->delete();
    }
};
