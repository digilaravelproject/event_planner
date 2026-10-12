<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dynamic_vendors')) {
            return;
        }

        DB::table('dynamic_vendors')->orderBy('id')->chunkById(100, function ($vendors): void {
            foreach ($vendors as $vendor) {
                $document = is_string($vendor->vendor_json) ? json_decode($vendor->vendor_json, true) : (array) $vendor->vendor_json;
                if (! is_array($document) || ! array_key_exists('availability', $document)) {
                    continue;
                }

                unset($document['availability']);
                DB::table('dynamic_vendors')->where('id', $vendor->id)->update(['vendor_json' => json_encode($document, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
        });
    }

    public function down(): void
    {
        // Removed schedule data cannot be reconstructed.
    }
};
