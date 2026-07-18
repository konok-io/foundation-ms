<?php

namespace App\Console\Commands;

use App\Models\MonthlyContribution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkOverdueContributions extends Command
{
    protected $signature = 'contributions:mark-overdue';

    protected $description = 'Mark pending contributions as overdue if past due date';

    public function handle(): int
    {
        $this->info('Marking overdue contributions...');

        $count = MonthlyContribution::where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} contributions as overdue.");

        Log::info('Overdue contributions marked via command', [
            'count' => $count,
        ]);

        return Command::SUCCESS;
    }
}
