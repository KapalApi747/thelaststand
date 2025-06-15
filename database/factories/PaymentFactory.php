<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethods = ['credit_card', 'paypal', 'stripe', 'bank_transfer'];
        $statuses = ['pending', 'paid', 'failed'];

        return [
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'transaction_id' => $this->faker->uuid(),
            'amount' => $this->faker->randomFloat(2, 5, 500), // realistic payment amount
            'status' => $this->faker->randomElement($statuses),
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
