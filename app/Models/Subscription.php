<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    public const INTERVALS = [
        'free' => 'Free',
        'three_months' => '3 Monthly',
        'six_months' => '6 Monthly',
        'yearly' => 'Yearly',
    ];

    protected $fillable = [
        'name',
        'price',
        'interval',
        'features',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
        ];
    }

    public function isFree(): bool
    {
        return $this->interval === 'free' || (float) $this->price === 0.0;
    }

    public function durationLabel(): string
    {
        if ($this->isFree()) {
            return '30 days';
        }

        return self::INTERVALS[$this->interval] ?? str($this->interval)->replace('_', ' ')->headline();
    }

    public function expirationDate()
    {
        return match ($this->interval) {
            'six_months' => now()->addMonths(6),
            'yearly' => now()->addYear(),
            'three_months' => now()->addMonths(3),
            default => now()->addDays(30),
        };
    }
}
