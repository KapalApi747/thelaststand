<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shippedAt = $this->faker->dateTimeBetween('-10 days', 'now');
        $deliveredAt = $this->faker->boolean(70) ? $this->faker->dateTimeBetween($shippedAt, 'now') : null;

        return [
            'order_id' => Order::factory(), // Can be overridden in seeder
            'tracking_number' => strtoupper($this->faker->bothify('TRK########')),
            'carrier' => $this->faker->randomElement(['UPS', 'FedEx', 'DHL', 'USPS', 'GLS']),
            'status' => $deliveredAt ? 'delivered' : 'shipped',
            'shipping_cost' => $this->faker->randomFloat(2, 5, 25),
            'shipping_method' => $this->faker->randomElement(['parcel', 'mail', 'pickup', 'express', 'courier']),
            'shipped_at' => $shippedAt,
            'delivered_at' => $deliveredAt,
        ];
    }
}
