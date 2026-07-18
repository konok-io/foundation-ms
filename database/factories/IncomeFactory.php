<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\IncomeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition(): array
    {
        return [
            'voucher_no' => Income::generateVoucherNo(),
            'category_id' => IncomeCategory::factory(),
            'member_id' => null,
            'amount' => fake()->randomElement([100, 200, 500, 1000, 2000, 5000]),
            'currency' => 'SAR',
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'check']),
            'received_from' => fake()->name(),
            'description' => fake()->sentence(),
            'date' => fake()->dateTimeBetween('-6 months', 'now'),
            'reference_no' => fake()->optional()->numerify('REF-####'),
            'attachment' => null,
            'created_by' => 1,
        ];
    }
}
