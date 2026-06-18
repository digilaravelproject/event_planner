<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type',
        'budget',
        'guests',
        'location',
        'date',
        'venue_type',
        'food_type',
        'style',
        'decoration_type',
        'entertainment_type',
        'budget_shares',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'budget_shares' => 'array',
        ];
    }

    /**
     * Get the user that owns the event plan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
