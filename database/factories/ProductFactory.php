<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
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
        $creationDate = Carbon::instance($this->faker->dateTimeBetween('-12 months', 'now', 'UTC'));

        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'sku' => strtoupper('SKU-' . Str::random(8)),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 5, 100),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean,
            'created_at' => $creationDate,
            'updated_at' => $creationDate,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {

            $creationDate = $product->created_at;

            // Attach categories
            $categoryIds = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $product->categories()->attach($categoryIds);

            // Create variants and force their timestamps
            $variants = ProductVariant::factory()
                ->count(rand(1, 3))
                ->for($product)
                ->create()
                ->each(function ($variant) use ($creationDate) {
                    $variant->forceFill([
                        'created_at' => $creationDate,
                        'updated_at' => $creationDate,
                    ])->save();
                });

            // Create product-level images
            $imageCount = rand(1, 4);
            for ($i = 0; $i < $imageCount; $i++) {
                $product->images()->create(
                    ProductImage::factory()->make([
                        'is_main_image' => $i === 0,
                        'created_at' => $creationDate,
                        'updated_at' => $creationDate,
                    ])->toArray()
                );
            }

            // Create variant-level images
            foreach ($variants as $variant) {
                ProductImage::factory()
                    ->count(1)
                    ->create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'created_at' => $creationDate,
                        'updated_at' => $creationDate,
                    ]);
            }
        });
    }

}
