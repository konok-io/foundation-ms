<?php

namespace Database\Factories;

use App\Models\Receipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
    protected $model = Receipt::class;

    public function definition(): array
    {
        return [
            'receipt_no' => Receipt::generateReceiptNo('RCP'),
            'type' => fake()->randomElement(['monthly_contribution', 'emergency_collection', 'donation']),
            'member_id' => null,
            'payment_id' => null,
            'contribution_id' => null,
            'emergency_payment_id' => null,
            'donation_id' => null,
            'amount' => fake()->randomElement([50, 100, 150, 200, 500]),
            'currency' => 'SAR',
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'online', 'check']),
            'paid_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'purpose' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'qr_code' => null,
            'is_printed' => fake()->boolean(30),
            'printed_at' => null,
            'is_emailed' => fake()->boolean(20),
            'emailed_at' => null,
            'created_by' => 1,
        ];
    }

    public function monthlyContribution(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'monthly_contribution',
            'purpose' => 'Monthly Contribution - ' . date('F Y'),
        ]);
    }

    public function emergencyCollection(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'emergency_collection',
            'purpose' => 'Emergency Collection Payment',
        ]);
    }

    public function donation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'donation',
            'purpose' => 'Donation',
        ]);
    }

    public function printed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_printed' => true,
            'printed_at' => now(),
        ]);
    }

    public function emailed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_emailed' => true,
            'emailed_at' => now(),
        ]);
    }
}
