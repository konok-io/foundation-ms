<?php

namespace Database\Factories;

use App\Models\IncomeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeCategoryFactory extends Factory
{
    protected $model = IncomeCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Monthly Contribution', 'Emergency Collection', 'Donation', 
                'Event Fee', 'Sponsorship', 'Grant', 'Membership Fee', 'Other Income'
            ]),
            'name_bn' => null,
            'code' => IncomeCategory::generateCode(),
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
