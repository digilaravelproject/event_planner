<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'business_name',
        'category',
        'city',
        'address',
        'state_id',
        'city_id',
        'area_id',
        'subarea_id',
        'status',
        'description',
        'base_price',
        'rating',
        'password',
        'costing_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'base_price' => 'decimal:2',
            'rating' => 'decimal:2',
            'password' => 'hashed',
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

    public function venue()
    {
        return $this->hasOne(Venue::class);
    }

    public function registries()
    {
        return $this->hasMany(VendorRegistry::class);
    }
}
