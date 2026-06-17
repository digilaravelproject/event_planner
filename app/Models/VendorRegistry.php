<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorRegistry extends Model
{
    protected $fillable = [
        'vendor_id',
        'event_type_id',
        'registry_key',
        'item_label',
        'share_percentage',
        'share_rupees',
        'status',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function eventType()
    {
        return $this->belongsTo(SystemMaster::class, 'event_type_id');
    }
}
