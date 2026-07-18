<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    protected $signature = 'reminders:send {type=all}';
    protected $description = 'Send various reminders to members';

    public function handle(ReminderService $reminderService)
    {
        $type = $this->argument('type');

        $this->info('Starting reminder service...');

        if ($type === 'all' || $type === 'birthday') {
            $birthdayCount = $reminderService->sendBirthdayReminders();
            $this->info("Birthday reminders sent: {$birthdayCount}");
        }

        if ($type === 'all' || $type === 'expiry') {
            $expiryCount = $reminderService->sendMembershipExpiryReminders();
            $this->info("Expiry reminders sent: {$expiryCount}");
        }

        if ($type === 'all' || $type === 'due') {
            $dueCount = $reminderService->sendDueReminders();
            $this->info("Due reminders sent: {$dueCount}");
        }

        $this->info('Reminder service completed.');
    }
}
