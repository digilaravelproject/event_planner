<?php

namespace App\Modules\DynamicVendors\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicVendorVersion extends Model
{
    protected $fillable = ['dynamic_vendor_id', 'version', 'vendor_json', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['vendor_json' => 'array'];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(DynamicVendor::class, 'dynamic_vendor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
