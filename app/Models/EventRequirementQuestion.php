<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRequirementQuestion extends Model
{
    protected $fillable = ['question', 'question_code', 'question_type', 'placeholder', 'options', 'option_metadata', 'vendor_attribute_key', 'vendor_attribute_label', 'vendor_attribute_values', 'vendor_attribute_images', 'is_required', 'display_order', 'status'];

    protected function casts(): array
    {
        return ['options' => 'array', 'option_metadata' => 'array', 'vendor_attribute_values' => 'array', 'vendor_attribute_images' => 'array', 'is_required' => 'boolean', 'status' => 'boolean'];
    }

    public function scopeEnabled($query)
    {
        return $query->where('status', true)->orderBy('display_order');
    }
}
