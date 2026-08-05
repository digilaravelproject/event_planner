<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminModuleOption extends Model
{
    protected $fillable = ['group', 'value', 'label', 'display_order', 'status'];
    protected function casts(): array { return ['status' => 'boolean']; }
    public function scopeForGroup($query, string $group) { return $query->where('group', $group)->where('status', true)->orderBy('display_order'); }
}
