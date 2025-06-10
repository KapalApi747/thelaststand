<?php

namespace Database\Factories;

use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerAddress>
 */
class CustomerAddressFactory extends Factory
{
    protected $model = CustomerAddress::class;

    public function definition(): array
    {
        return [
            'type'           => 'shipping',
            'address_line1'  => $this->faker->streetAddress(),
            'address_line2'  => $this->faker->optional()->secondaryAddress(),
            'city'           => $this->faker->city(),
            'state'          => $this->faker->state(),
            'zip'            => $this->faker->postcode(),
            'country'        => $this->faker->country(),
        ];
    }
}
