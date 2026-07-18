<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Commands
Schedule::command('contributions:generate-monthly-due')->monthlyOn(1, '00:00');
Schedule::command('contributions:send-reminders')->weeklyOn(0, '09:00');
Schedule::command('activity-logs:clean')->weekly();
Schedule::command('backup:clean')->daily();
