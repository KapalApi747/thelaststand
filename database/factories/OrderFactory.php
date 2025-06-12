<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 10, 500);
        $tax = $subtotal * 0.21;
        $shipping = $this->faker->randomFloat(2, 5, 20);
        $status = $this->faker->randomFloat(2, 0, 1) < 0.9
            ? 'completed'
            : $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'refunded', 'failed', 'cancelled']);

        return [
            'order_number' => strtoupper('ORD-' . $this->faker->unique()->bothify('#######')), // e.g. ORD-1234567
            'total_amount' => round($subtotal + $tax + $shipping, 2),
            'tax_amount' => round($tax, 2),
            'shipping_cost' => round($shipping, 2),
            'status' => $status,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
