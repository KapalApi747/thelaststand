<?php

namespace App\Livewire\Tenant\Frontend\Shopping\Products;

use App\Models\Product;
use App\Models\ProductReview;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class ProductShow extends Component
{
    public Product $product;
    public array $customerReviewedProductIds = [];

    public $variantId;

    public function mount($slug)
    {
        $this->product = Product::with([
            'mainImage',
            'images',
            'categories',
            'reviews.replies',
            'reviews.customer',
            'variants',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (auth('customer')->check()) {
            $this->customerReviewedProductIds = ProductReview::where('customer_id', auth('customer')->id())
                ->pluck('product_id')
                ->toArray();
        } else {
            $this->customerReviewedProductIds = [];
        }
    }

    public function selectedVariant()
    {
        return $this->product->variants->firstWhere('id', $this->variantId);
    }

    public function updatedVariantId()
    {
        //
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.products.product-show');
    }
}
