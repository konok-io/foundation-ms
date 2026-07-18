<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'voucher_no' => Expense::generateVoucherNo(),
            'category_id' => ExpenseCategory::factory(),
            'payee_name' => fake()->name(),
            'member_id' => null,
            'amount' => fake()->randomElement([50, 100, 200, 500, 1000, 2000]),
            'currency' => 'SAR',
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'check']),
            'description' => fake()->sentence(),
            'date' => fake()->dateTimeBetween('-6 months', 'now'),
            'reference_no' => fake()->optional()->numerify('REF-####'),
            'attachment' => null,
            'approved_by' => 1,
            'created_by' => 1,
        ];
    }
}
