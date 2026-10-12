<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuery extends Model
{
    protected $fillable = ['user_id', 'name', 'email', 'phone', 'subject', 'message', 'status', 'admin_reply', 'replied_at'];

    protected function casts(): array
    {
        return ['replied_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
