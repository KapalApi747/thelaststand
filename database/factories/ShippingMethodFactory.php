<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingMethod>
 */
class ShippingMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => 'Parcel (Standard Shipping)',
            'code' => 'parcel',
            'cost' => 5.00,
            'carriers' => json_encode(['PostNL', 'DHL', 'FedEx', 'UPS']),
            'enabled' => true,
        ];
    }
}
