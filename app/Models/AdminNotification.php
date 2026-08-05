<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminNotification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['title', 'message', 'notification_type', 'status', 'schedule_at', 'created_by', 'sent_at'];
    protected function casts(): array { return ['schedule_at' => 'datetime', 'sent_at' => 'datetime']; }
    public function creator(): BelongsTo { return $this->belongsTo(Admin::class, 'created_by'); }
    public function users(): BelongsToMany { return $this->belongsToMany(User::class, 'notification_users')->withPivot(['is_read', 'read_at'])->withTimestamps(); }
}
