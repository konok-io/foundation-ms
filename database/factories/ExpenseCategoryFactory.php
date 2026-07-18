<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseCategoryFactory extends Factory
{
    protected $model = ExpenseCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Medical Support', 'Education Support', 'Administrative Expense',
                'Events', 'Emergency Aid', 'Equipment', 'Utilities', 'Rent', 'Salary', 'Other Expense'
            ]),
            'name_bn' => null,
            'code' => ExpenseCategory::generateCode(),
            'description' => fake()->sentence(),
            'is_active' => true,
            'created_by' => 1,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
