<?php

namespace App\Modules\DynamicVendors\Models;

use App\Models\Admin;
use App\Models\VendorAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DynamicVendor extends Model
{
    protected $table = 'vendors_dynamic';

    protected $fillable = ['vendor_json', 'status', 'vendor_account_id', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['vendor_json' => 'array'];
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DynamicVendorVersion::class)->latest('version');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function vendorAccount(): BelongsTo
    {
        return $this->belongsTo(VendorAccount::class);
    }

    public function getNameAttribute(): string
    {
        return (string) data_get($this->vendor_json, 'identity.name', 'Untitled vendor');
    }

    public function getCategoryAttribute(): string
    {
        return (string) data_get($this->vendor_json, 'identity.category', 'Uncategorised');
    }
}
