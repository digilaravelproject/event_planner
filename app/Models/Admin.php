<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $attributes = [
        'role' => 'administrator',
        'is_active' => true,
    ];

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'permissions',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role !== 'staff';
    }

    public function canAccess(string $permission): bool
    {
        if ($permission === 'staff') {
            return $this->isSuperAdmin();
        }

        return $this->isSuperAdmin() || in_array($permission, $this->permissions ?? [], true);
    }

    public function createdNotifications()
    {
        return $this->hasMany(AdminNotification::class, 'created_by');
    }
}
