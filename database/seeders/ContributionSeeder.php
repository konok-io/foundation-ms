<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MonthlyContribution;
use Illuminate\Database\Seeder;

class ContributionSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::active()->get();
        $year = date('Y');
        $amount = 100;

        // Create contributions for the last 6 months
        for ($month = 1; $month <= date('n'); $month++) {
            foreach ($members as $member) {
                // Randomly decide if this member has paid
                $isPaid = fake()->randomElement([true, true, false, false]);

                MonthlyContribution::create([
                    'member_id' => $member->id,
                    'year' => $year,
                    'month' => $month,
                    'amount' => $amount,
                    'paid_amount' => $isPaid ? $amount : 0,
                    'due_amount' => $isPaid ? 0 : $amount,
                    'due_date' => date('Y-m-d', strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-28')),
                    'paid_date' => $isPaid ? date('Y-m-d', strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . fake()->numberBetween(1, 28))) : null,
                    'status' => $isPaid ? 'paid' : ($month < date('n') ? 'overdue' : 'pending'),
                    'payment_method' => $isPaid ? 'cash' : null,
                    'transaction_id' => $isPaid ? 'TXN-' . fake()->numerify('######') : null,
                    'receipt_no' => $isPaid ? 'RCP-' . date('Ymd') . '-' . str_pad(fake()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT) : null,
                    'created_by' => 1,
                    'paid_by' => $isPaid ? 1 : null,
                ]);
            }
        }

        $this->command->info('Contributions seeded for ' . count($members) . ' members across ' . date('n') . ' months.');
    }
}
