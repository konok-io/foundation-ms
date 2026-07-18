<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        return [
            'donor_name' => fake()->name(),
            'donor_name_bn' => null,
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'member_id' => null,
            'amount' => fake()->randomElement([50, 100, 200, 500, 1000, 5000]),
            'currency' => 'SAR',
            'purpose' => fake()->randomElement(array_keys(Donation::PURPOSES)),
            'purpose_other' => null,
            'payment_method' => fake()->randomElement(array_keys(Donation::PAYMENT_METHODS)),
            'gateway' => null,
            'gateway_transaction_id' => null,
            'payment_id' => null,
            'status' => fake()->randomElement(['pending', 'completed', 'completed', 'completed']),
            'is_anonymous' => fake()->boolean(10),
            'message' => fake()->optional()->sentence(),
            'received_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'received_by' => 1,
            'notes' => null,
            'created_by' => 1,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'received_at' => null,
            'received_by' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'received_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'received_by' => 1,
        ]);
    }

    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_anonymous' => true,
        ]);
    }

    public function medical(): static
    {
        return $this->state(fn (array $attributes) => [
            'purpose' => 'medical',
        ]);
    }

    public function education(): static
    {
        return $this->state(fn (array $attributes) => [
            'purpose' => 'education',
        ]);
    }

    public function emergency(): static
    {
        return $this->state(fn (array $attributes) => [
            'purpose' => 'emergency',
        ]);
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'online',
            'gateway' => fake()->randomElement(['stripe', 'paypal']),
        ]);
    }
}
