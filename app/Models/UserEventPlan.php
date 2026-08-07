<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserEventPlan extends Model
{
    protected $fillable = [
        'user_id', 'parent_plan_id', 'title', 'category', 'guest_count', 'answers',
        'requirement_prompt', 'vendor_snapshot', 'summary', 'total_cost', 'model',
        'status', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'vendor_snapshot' => 'array',
            'summary' => 'array',
            'total_cost' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_plan_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_plan_id')->orderBy('total_cost');
    }
}
