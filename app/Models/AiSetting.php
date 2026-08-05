<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Helper to get a setting value.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Helper to set a setting value.
     */
    public static function setValue(string $key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

}
