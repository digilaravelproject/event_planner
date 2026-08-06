<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    public const TYPES = [
        'how-it-works' => 'How It Works',
        'comparisons' => 'Comparison',
        'testimonials' => 'User Testimonials',
    ];

    protected $fillable = ['type', 'title', 'subtitle', 'body', 'image', 'meta', 'display_order', 'status'];

    protected function casts(): array
    {
        return ['meta' => 'array', 'status' => 'boolean'];
    }

    public function scopePublished($query)
    {
        return $query->where('status', true)->orderBy('display_order')->orderBy('id');
    }

    public static function label(string $type): string
    {
        abort_unless(isset(self::TYPES[$type]), 404);

        return self::TYPES[$type];
    }
}
