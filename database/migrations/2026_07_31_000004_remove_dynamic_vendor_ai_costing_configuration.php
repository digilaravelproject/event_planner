<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->cleanTable('vendors_dynamic');
        $this->cleanTable('dynamic_vendor_versions');
    }

    public function down(): void
    {
        // Removed configuration cannot be reconstructed safely.
    }

    private function cleanTable(string $table): void
    {
        DB::table($table)->orderBy('id')->chunkById(100, function ($rows) use ($table): void {
            foreach ($rows as $row) {
                $document = json_decode($row->vendor_json, true);
                if (! is_array($document)) {
                    continue;
                }

                $document['schema_version'] = 1;

                foreach ($document['attributes'] ?? [] as $index => $attribute) {
                    unset(
                        $document['attributes'][$index]['ai'],
                        $document['attributes'][$index]['costing'],
                    );
                }

                DB::table($table)->where('id', $row->id)->update([
                    'vendor_json' => json_encode($document, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
            }
        });
    }
};
