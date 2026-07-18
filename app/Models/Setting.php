<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    const TYPES = [
        'text' => 'Text',
        'number' => 'Number',
        'email' => 'Email',
        'textarea' => 'Textarea',
        'boolean' => 'Boolean',
        'select' => 'Select',
        'image' => 'Image',
        'file' => 'File',
        'color' => 'Color',
        'json' => 'JSON',
    ];

    const GROUPS = [
        'general' => 'General',
        'appearance' => 'Appearance',
        'email' => 'Email',
        'sms' => 'SMS',
        'payment' => 'Payment',
        'social' => 'Social Media',
        'seo' => 'SEO',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'text', $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }

    public static function getAllByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    public static function getAllSettings()
    {
        $settings = self::all();
        return $settings->pluck('value', 'key')->toArray();
    }
}
