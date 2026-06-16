<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'state_id',
        'city_id',
        'area_id',
        'subarea_id',
        'capacity',
        'price_per_day',
        'status',
        'vendor_id',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'price_per_day' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function subarea()
    {
        return $this->belongsTo(Subarea::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
