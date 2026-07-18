<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public static function getSetting(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setSetting(string $key, $value, string $type = 'text', string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }

    public static function getSettingsByGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    public static function defaultSettings(): array
    {
        return [
            'site_name' => ['value' => 'Foundation Management System', 'type' => 'text', 'group' => 'general'],
            'site_tagline' => ['value' => 'Building a Better Tomorrow', 'type' => 'text', 'group' => 'general'],
            'site_description' => ['value' => 'Social Welfare Foundation', 'type' => 'textarea', 'group' => 'general'],
            'address' => ['value' => '', 'type' => 'textarea', 'group' => 'contact'],
            'phone' => ['value' => '', 'type' => 'text', 'group' => 'contact'],
            'email' => ['value' => '', 'type' => 'email', 'group' => 'contact'],
            'map_embed' => ['value' => '', 'type' => 'textarea', 'group' => 'contact'],
            'facebook' => ['value' => '', 'type' => 'url', 'group' => 'social'],
            'twitter' => ['value' => '', 'type' => 'url', 'group' => 'social'],
            'instagram' => ['value' => '', 'type' => 'url', 'group' => 'social'],
            'linkedin' => ['value' => '', 'type' => 'url', 'group' => 'social'],
            'youtube' => ['value' => '', 'type' => 'url', 'group' => 'social'],
            'currency' => ['value' => 'SAR', 'type' => 'text', 'group' => 'general'],
            'currency_symbol' => ['value' => 'ر.س', 'type' => 'text', 'group' => 'general'],
            'timezone' => ['value' => 'Asia/Dhaka', 'type' => 'select', 'group' => 'general'],
            'locale' => ['value' => 'en', 'type' => 'select', 'group' => 'general'],
            'logo' => ['value' => '', 'type' => 'file', 'group' => 'appearance'],
            'favicon' => ['value' => '', 'type' => 'file', 'group' => 'appearance'],
            'primary_color' => ['value' => '#4F46E5', 'type' => 'color', 'group' => 'appearance'],
            'secondary_color' => ['value' => '#10B981', 'type' => 'color', 'group' => 'appearance'],
        ];
    }
}
