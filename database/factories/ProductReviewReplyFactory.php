<?php

namespace Database\Factories;

use App\Models\ProductReviewReply;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductReviewReply>
 */
class ProductReviewReplyFactory extends Factory
{
    protected $model = ProductReviewReply::class;

    public function definition(): array
    {
        return [
            'body' => $this->faker->sentence(),
        ];
    }
}
