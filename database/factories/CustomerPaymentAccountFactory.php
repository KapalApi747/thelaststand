<?php

namespace Database\Factories;

use App\Models\CustomerPaymentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerPaymentAccount>
 */
class CustomerPaymentAccountFactory extends Factory
{
    protected $model = CustomerPaymentAccount::class;

    public function definition(): array
    {
        return [
            'provider'             => $this->faker->randomElement(['stripe', 'paypal', 'creditcard', 'banktransfer']),
            'provider_customer_id' => $this->faker->uuid(),
        ];
    }
}
