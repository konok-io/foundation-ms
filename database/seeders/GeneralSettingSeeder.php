<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;

class GeneralSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'Foundation MS', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Building a Better Tomorrow', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'A comprehensive foundation management system for social welfare organizations.', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'currency', 'value' => 'SAR', 'type' => 'text', 'group' => 'general'],
            ['key' => 'currency_symbol', 'value' => 'ر.س', 'type' => 'text', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'type' => 'select', 'group' => 'general'],
            ['key' => 'locale', 'value' => 'en', 'type' => 'select', 'group' => 'general'],
            
            // Contact
            ['key' => 'address', 'value' => '123 Foundation Street, Dhaka, Bangladesh', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'phone', 'value' => '+880 1700-000000', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'info@foundation.org', 'type' => 'email', 'group' => 'contact'],
            
            // Social Media
            ['key' => 'facebook', 'value' => 'https://facebook.com/foundation', 'type' => 'url', 'group' => 'social'],
            ['key' => 'twitter', 'value' => 'https://twitter.com/foundation', 'type' => 'url', 'group' => 'social'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/foundation', 'type' => 'url', 'group' => 'social'],
            ['key' => 'linkedin', 'value' => 'https://linkedin.com/company/foundation', 'type' => 'url', 'group' => 'social'],
            ['key' => 'youtube', 'value' => 'https://youtube.com/foundation', 'type' => 'url', 'group' => 'social'],
            
            // Appearance
            ['key' => 'logo', 'value' => '', 'type' => 'file', 'group' => 'appearance'],
            ['key' => 'favicon', 'value' => '', 'type' => 'file', 'group' => 'appearance'],
            ['key' => 'primary_color', 'value' => '#4F46E5', 'type' => 'color', 'group' => 'appearance'],
            ['key' => 'secondary_color', 'value' => '#10B981', 'type' => 'color', 'group' => 'appearance'],
        ];

        foreach ($settings as $setting) {
            GeneralSetting::firstOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                ]
            );
        }

        $this->command->info('General settings seeded successfully!');
    }
}
