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
        'state_id',
        'city_id',
        'area_id',
        'subarea_id',
        'date',
        'venue_type',
        'food_type',
        'style',
        'decoration_type',
        'entertainment_type',
        'dynamic_selections',
        'budget_shares',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'dynamic_selections' => 'array',
            'budget_shares' => 'array',
        ];
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function subarea()
    {
        return $this->belongsTo(Subarea::class);
    }

    /**
     * Get the user that owns the event plan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
