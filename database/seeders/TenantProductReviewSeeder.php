<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::inRandomOrder()->take(100)->get();

        Product::with('reviews')->get()->each(function ($product) use ($customers) {
            $customerPool = $customers->shuffle();
            $reviewCount = rand(10, 30);
            $usedCustomerIds = [];

            for ($i = 0; $i < $reviewCount && $i < $customerPool->count(); $i++) {
                $customer = $customerPool[$i];

                // Check to ensure the customer hasn't already reviewed this product
                if ($product->reviews->where('customer_id', $customer->id)->isEmpty()) {
                    $review = ProductReview::factory()->create([
                        'product_id' => $product->id,
                        'customer_id' => $customer->id,
                    ]);

                    // Random number of replies (0–5)
                    $replyCount = rand(0, 5);
                    for ($j = 0; $j < $replyCount; $j++) {
                        ProductReviewReply::factory()->create([
                            'product_review_id' => $review->id,
                            'customer_id' => $customers->random()->id,
                        ]);
                    }
                }
            }
        });
    }
}
