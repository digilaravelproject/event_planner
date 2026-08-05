<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $fillable = ['notification_id', 'user_id', 'is_read', 'read_at'];
    protected function casts(): array { return ['is_read' => 'boolean', 'read_at' => 'datetime']; }
    public function notification() { return $this->belongsTo(AdminNotification::class, 'notification_id'); }
    public function user() { return $this->belongsTo(User::class); }
}
