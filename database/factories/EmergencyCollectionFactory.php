<?php

namespace Database\Factories;

use App\Models\EmergencyCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyCollectionFactory extends Factory
{
    protected $model = EmergencyCollection::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'title_bn' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'description_bn' => fake()->paragraph(),
            'type' => fake()->randomElement(['medical', 'natural_disaster', 'funeral', 'flood_relief', 'earthquake', 'fire_relief', 'education', 'other']),
            'target_amount' => fake()->randomElement([5000, 10000, 25000, 50000]),
            'collected_amount' => fake()->numberBetween(0, 10000),
            'amount_per_member' => fake()->randomElement([50, 100, 200]),
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['draft', 'active', 'closed', 'cancelled']),
            'created_by' => 1,
            'notes' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function medical(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'medical',
            'title' => 'Medical Emergency Fund - ' . fake()->lastName(),
        ]);
    }

    public function floodRelief(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'flood_relief',
            'title' => 'Flood Relief Campaign ' . date('Y'),
        ]);
    }
}
