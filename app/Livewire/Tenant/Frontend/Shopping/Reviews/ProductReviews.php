<?php

namespace App\Livewire\Tenant\Frontend\Shopping\Reviews;

use App\Models\Product;
use App\Models\ProductReview;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class ProductReviews extends Component
{
    public $product;
    public $rating;
    public $comment;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $customerId = auth('customer')->id();

        $alreadyReviewed = ProductReview::where('product_id', $this->product->id)
            ->where('customer_id', $customerId)
            ->exists();

        if ($alreadyReviewed) {
            session()->flash('message', 'You have already reviewed this product!');
            return;
        }

        ProductReview::create([
            'product_id' => $this->product->id,
            'customer_id' => auth('customer')->id() ?? null,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => true,
        ]);

        session()->flash('message', 'Thank you for your review!');

        $this->reset(['rating', 'comment']);
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.reviews.product-reviews');
    }
}
