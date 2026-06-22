<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'event_plan_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function eventPlan()
    {
        return $this->belongsTo(EventPlan::class);
    }
}
