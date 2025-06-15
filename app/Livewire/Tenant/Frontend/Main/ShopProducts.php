<?php

namespace App\Livewire\Tenant\Frontend\Main;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[layout('t-shop-layout')]
class ShopProducts extends Component
{
    public Product $product;
    public $products;
    public $categories;

    public $selectedCategories = [];
    public $minPrice = null;
    public $maxPrice = null;

    public $rating = null;
    public $comment = '';
    public $hasReviewed = false;
    public $showReplies = [];
    public $customerReviewedProductIds = [];

    public $replyBodies = [];
    public $replies = [];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->categories = Category::all();
        $this->loadProducts();

        if (auth('customer')->check()) {
            $this->customerReviewedProductIds = ProductReview::where('customer_id', auth('customer')->id())
                ->pluck('product_id')
                ->toArray();
        } else {
            $this->customerReviewedProductIds = [];
        }
    }

    public function updatedSelectedCategories()
    {
        $this->loadProducts();
    }

    public function updatedMinPrice()
    {
        $this->loadProducts();
    }

    public function updatedMaxPrice()
    {
        $this->loadProducts();
    }

    protected function loadProducts()
    {
        $query = Product::with([
            'images',
            'categories',
            'variants.images',
            'reviews' => fn ($query) => $query->where('is_approved', true)
        ])
            ->withCount([
                'reviews as approved_reviews_count' => fn ($query) => $query->where('is_approved', true)
            ])
            ->withAvg([
                'reviews as average_rating' => fn ($query) => $query->where('is_approved', true)
            ], 'rating')
            ->where('is_active', 1);

        if (!empty($this->selectedCategories)) {
            foreach ($this->selectedCategories as $categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }
        }

        if ($this->minPrice !== null) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice !== null) {
            $query->where('price', '<=', $this->maxPrice);
        }

        $this->products = $query->get();
    }

    public function submitReview($productId)
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $product = Product::findOrFail($productId);
        $customerId = auth('customer')->id();

        $alreadyReviewed = ProductReview::where('product_id', $productId)
            ->where('customer_id', $customerId)
            ->exists();

        if ($alreadyReviewed) {
            $this->session()->flash('message', 'You have already reviewed this product!');
            return;
        }

        ProductReview::create([
            'product_id' => $product->id,
            'customer_id' => $customerId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => true,
        ]);

        $this->reset(['rating', 'comment']);
        $this->hasReviewed = true;

        $this->refreshProductReviews($productId);

        $this->session()->flash('message', 'Thank you for your review!');
    }

    protected function refreshProductReviews($productId)
    {
        $this->product = Product::withCount([
            'reviews as approved_reviews_count' => fn ($q) => $q->where('is_approved', true),
        ])
            ->withAvg([
                'reviews as average_rating' => fn ($q) => $q->where('is_approved', true),
            ], 'rating')
            ->with([
                'reviews' => fn ($q) => $q->where('is_approved', true)->with('customer'),
            ])
            ->findOrFail($productId);
    }

    public function toggleReplies($reviewId)
    {
        if (in_array($reviewId, $this->showReplies)) {
            $this->showReplies = array_diff($this->showReplies, [$reviewId]);
            unset($this->replies[$reviewId]);
        } else {
            $this->showReplies[] = $reviewId;
            $this->loadReplies($reviewId);
        }

        $this->loadProducts();
    }

    protected function loadReplies($reviewId)
    {
        $this->replies[$reviewId] = ProductReviewReply::with('customer')
            ->where('product_review_id', $reviewId)
            ->latest()
            ->get();
    }

    public function submitReply($reviewId)
    {
        $this->validate([
            "replyBodies.$reviewId" => 'required|string|max:1000',
        ]);

        ProductReviewReply::create([
            'product_review_id' => $reviewId,
            'customer_id' => auth('customer')->id(),
            'body' => $this->replyBodies[$reviewId],
        ]);

        $this->replyBodies[$reviewId] = '';
        $this->loadProducts();
        $this->loadReplies($reviewId);

        session()->flash('review_message', 'Your reply has been submitted!');
    }

    public function render()
    {
        $reviews = $this->product->reviews()
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return view('livewire.tenant.frontend.main.shop-products', compact('reviews'))->with([
            'products' => $this->products,
            'categories' => $this->categories,
        ]);
    }

}
