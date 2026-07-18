<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Commands

// Generate monthly contributions on 1st of each month at midnight
Schedule::command('contributions:generate --year=' . date('Y') . ' --month=' . date('n'))->monthlyOn(1, '00:00');

// Mark overdue contributions daily at 1 AM
Schedule::command('contributions:mark-overdue')->dailyAt('01:00');

// Activity Logs cleanup weekly
Schedule::command('activity-logs:clean')->weekly();

// Backup cleanup daily
Schedule::command('backup:clean')->daily();
