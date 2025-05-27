<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->name(),
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => $this->faker->phoneNumber(),
            'password'      => Hash::make('password'), // default password for testing
            'address_line1' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->optional()->secondaryAddress(),
            'city'          => $this->faker->city(),
            'state'         => $this->faker->stateAbbr(),
            'zip'           => $this->faker->postcode(),
            'country'       => $this->faker->country(),
            'is_active'     => $this->faker->boolean(90), // 90% chance to be active
        ];
    }
}
