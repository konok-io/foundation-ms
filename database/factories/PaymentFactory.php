<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $isCompleted = fake()->randomElement([true, true, false]);
        
        return [
            'payment_id' => Payment::generatePaymentId(),
            'member_id' => null,
            'type' => fake()->randomElement(['monthly_contribution', 'emergency_collection', 'donation', 'event_fee']),
            'reference_id' => null,
            'reference_type' => null,
            'amount' => fake()->randomElement([50, 100, 150, 200, 500]),
            'currency' => 'SAR',
            'payment_method' => 'online',
            'gateway' => fake()->randomElement(['stripe', 'paypal']),
            'gateway_transaction_id' => 'txn_' . fake()->unique()->uuid(),
            'gateway_response' => null,
            'status' => $isCompleted ? 'completed' : 'pending',
            'paid_at' => $isCompleted ? fake()->dateTimeBetween('-3 months', 'now') : null,
            'failure_reason' => null,
            'created_by' => 1,
        ];
    }

    public function stripe(): static
    {
        return $this->state(fn (array $attributes) => [
            'gateway' => 'stripe',
        ]);
    }

    public function paypal(): static
    {
        return $this->state(fn (array $attributes) => [
            'gateway' => 'paypal',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'failure_reason' => fake()->sentence(),
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
            'refund_id' => 'REF-' . fake()->unique()->uuid(),
            'refund_amount' => $attributes['amount'] ?? 100,
            'refund_reason' => 'Customer request',
            'refunded_at' => now(),
        ]);
    }
}
