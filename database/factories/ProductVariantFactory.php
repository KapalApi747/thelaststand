<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(), // Overridden when called from ProductFactory
            'name' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Large', 'Small']),
            'sku' => strtoupper('VAR-' . Str::random(8)),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'stock' => $this->faker->numberBetween(0, 50),
            'is_active' => true,
        ];
    }
}
