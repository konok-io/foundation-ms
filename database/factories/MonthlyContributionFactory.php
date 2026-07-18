<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MonthlyContribution;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthlyContributionFactory extends Factory
{
    protected $model = MonthlyContribution::class;

    public function definition(): array
    {
        $amount = fake()->randomElement([50, 100, 150, 200]);
        $isPaid = fake()->randomElement([true, false, false, false]);

        return [
            'member_id' => Member::factory(),
            'year' => fake()->numberBetween(2024, date('Y')),
            'month' => fake()->numberBetween(1, 12),
            'amount' => $amount,
            'paid_amount' => $isPaid ? $amount : 0,
            'due_amount' => $isPaid ? 0 : $amount,
            'due_date' => fake()->dateTimeBetween('-6 months', '+1 month'),
            'paid_date' => $isPaid ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'status' => $isPaid ? 'paid' : 'pending',
            'payment_method' => $isPaid ? fake()->randomElement(['cash', 'bank_transfer', 'online']) : null,
            'transaction_id' => $isPaid ? 'TXN-' . fake()->unique()->numerify('######') : null,
            'receipt_no' => $isPaid ? 'RCP-' . fake()->unique()->numerify('######') : null,
            'notes' => null,
            'created_by' => 1,
            'paid_by' => $isPaid ? 1 : null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_amount' => $attributes['amount'],
            'due_amount' => 0,
            'paid_date' => now(),
            'payment_method' => 'cash',
            'receipt_no' => 'RCP-' . fake()->unique()->numerify('######'),
            'paid_by' => 1,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_amount' => 0,
            'due_amount' => $attributes['amount'],
            'paid_date' => null,
            'payment_method' => null,
            'receipt_no' => null,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => fake()->dateTimeBetween('-3 months', '-1 month'),
        ]);
    }

    public function forPeriod(int $year, int $month): static
    {
        return $this->state(fn (array $attributes) => [
            'year' => $year,
            'month' => $month,
        ]);
    }
}
