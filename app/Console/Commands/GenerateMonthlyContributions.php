<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\MonthlyContribution;
use App\Models\GeneralSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyContributions extends Command
{
    protected $signature = 'contributions:generate 
                            {--year= : The year to generate contributions for}
                            {--month= : The month to generate contributions for}
                            {--amount= : The contribution amount per member}
                            {--dry-run : Preview without creating}';

    protected $description = 'Generate monthly contributions for all active members';

    public function handle(): int
    {
        $year = $this->option('year') ?? date('Y');
        $month = $this->option('month') ?? date('n');
        $amount = $this->option('amount') ?? GeneralSetting::getSetting('default_contribution_amount', 100);
        $dryRun = $this->option('dry-run');

        $this->info("Generating monthly contributions for {$month}/{$year}...");

        $members = Member::active()->get();
        $created = 0;
        $skipped = 0;

        foreach ($members as $member) {
            $existing = MonthlyContribution::where('member_id', $member->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($existing) {
                $this->line("  [SKIP] {$member->member_id} - {$member->name} (already exists)");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  [DRY] {$member->member_id} - {$member->name} - {$amount} SAR");
            } else {
                MonthlyContribution::create([
                    'member_id' => $member->id,
                    'year' => $year,
                    'month' => $month,
                    'amount' => $amount,
                    'paid_amount' => 0,
                    'due_amount' => $amount,
                    'due_date' => date('Y-m-d', strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-28')),
                    'status' => 'pending',
                    'created_by' => 1,
                ]);
                $this->line("  [CREATED] {$member->member_id} - {$member->name}");
            }
            $created++;
        }

        if ($dryRun) {
            $this->info("Dry run complete. Would create {$created} contributions, skip {$skipped} existing.");
        } else {
            $this->info("Complete! Created {$created} contributions, skipped {$skipped} existing.");
            
            Log::info('Monthly contributions generated via command', [
                'year' => $year,
                'month' => $month,
                'amount' => $amount,
                'created' => $created,
                'skipped' => $skipped,
            ]);
        }

        return Command::SUCCESS;
    }
}
