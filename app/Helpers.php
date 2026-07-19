<?php

use App\Models\GeneralSetting;

if (!function_exists('settings')) {
    function settings(string $key, $default = null)
    {
        return GeneralSetting::getSetting($key, $default);
    }
}
