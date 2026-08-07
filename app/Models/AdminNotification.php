<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class AdminNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['title', 'message', 'notification_type', 'status', 'schedule_at', 'created_by', 'sent_at'];

    protected function casts(): array
    {
        return ['schedule_at' => 'datetime', 'sent_at' => 'datetime'];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_users', 'notification_id', 'user_id')->withPivot(['is_read', 'read_at'])->withTimestamps();
    }

    public function scopeVisibleToUsers(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('status', 'sent')
                ->orWhere(function (Builder $query): void {
                    $query->where('status', 'scheduled')->whereNotNull('schedule_at')->where('schedule_at', '<=', now());
                });
        });
    }
}
