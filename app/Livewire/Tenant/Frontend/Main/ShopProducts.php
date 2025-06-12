<?php

namespace App\Livewire\Tenant\Frontend\Main;

use App\Models\Product;
use App\Models\ProductReview;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[layout('t-shop-layout')]
class ShopProducts extends Component
{
    public $products;
    public $selectedProduct;
    public $customerReviewedProductIds = [];

    public function mount()
    {
        $this->loadProducts();
        $this->loadCustomerReviews();
    }

    protected function loadProducts()
    {
        $this->products = Product::with([
            'images',
            'categories',
            'variants.images',
            'reviews' => function ($query) {
                $query->where('is_approved', true);
            }
        ])
            ->withCount([
                'reviews as approved_reviews_count' => fn ($query) => $query->where('is_approved', true)
            ])
            ->withAvg([
                'reviews as average_rating' => fn ($query) => $query->where('is_approved', true)
            ], 'rating')
            ->where('is_active', 1)
            ->get();
    }

    protected function loadCustomerReviews()
    {
        if ($customerId = auth('customer')->id()) {
            $this->customerReviewedProductIds = ProductReview::where('customer_id', $customerId)
                ->pluck('product_id')
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.tenant.frontend.main.shop-products', [
            'products' => $this->products,
            'customerReviewedProductIds' => $this->customerReviewedProductIds,
        ]);
    }
}
