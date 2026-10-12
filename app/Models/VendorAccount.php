<?php

namespace App\Models;

use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class VendorAccount extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'business_name', 'email', 'phone', 'category', 'city',
        'address', 'description', 'password', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    public function dynamicVendors(): HasMany
    {
        return $this->hasMany(DynamicVendor::class);
    }
}
