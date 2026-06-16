<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorRegistry extends Model
{
    protected $fillable = [
        'vendor_id',
        'registry_key',
        'item_label',
        'share_percentage',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
