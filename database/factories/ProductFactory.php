<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'sku' => strtoupper('SKU-' . Str::random(8)),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 5, 100),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {

            $categoryIds = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $product->categories()->attach($categoryIds);

            $variants = ProductVariant::factory()
                ->count(rand(1, 3))
                ->for($product)
                ->create();

            $imageCount = rand(1, 4);
            for ($i = 0; $i < $imageCount; $i++) {
                $product->images()->create(
                    ProductImage::factory()->make([
                        'is_main_image' => $i === 0, // Only first image is marked main
                    ])->toArray()
                );
            }

            foreach ($variants as $variant) {
                ProductImage::factory()
                    ->count(1)
                    ->create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                    ]);
            }

            /*foreach ($variants as $variant) {
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $variant->images()->create(
                        ProductImage::factory()->make([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'is_main_image' => $i === 0,
                        ])->toArray()
                    );
                }
            }*/
        });
    }
}
