<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'subscription_id',
        'subscription_ends_at',
        'razorpay_payment_id',
        'razorpay_order_id',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * Get the user's subscription details.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function adminNotifications()
    {
        return $this->belongsToMany(AdminNotification::class, 'notification_users', 'user_id', 'notification_id')
            ->withPivot(['is_read', 'read_at'])->withTimestamps();
    }

    public function eventPlans()
    {
        return $this->hasMany(UserEventPlan::class);
    }

    public function subscriptionHistory()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function queries()
    {
        return $this->hasMany(UserQuery::class);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription_id !== null
            && ($this->subscription_ends_at === null || $this->subscription_ends_at->isFuture());
    }

    public function subscriptionFlag(): string
    {
        if ($this->subscription_id !== null && $this->subscription_ends_at?->isPast()) {
            return 'expired';
        }

        if ($this->subscription_id === null || (float) ($this->subscription?->price ?? 0) === 0.0) {
            return 'free';
        }

        return 'subscribed';
    }

    public function hasPaidSubscription(): bool
    {
        return $this->hasActiveSubscription() && ! $this->subscription?->isFree();
    }
}
