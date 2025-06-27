<?php

namespace App\Livewire\Tenant\Frontend\Shopping\Reviews;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('t-shop-layout')]
class ProductReviews extends Component
{
    use WithPagination;

    public $product;
    public $rating;
    public $comment;
    public array $customerReviewedProductIds = [];
    public bool $hasPurchased = false;

    public $showReplies = [];

    public $editingReviewId;
    public $editingRating;
    public $editingComment;


    public function mount(Product $product)
    {
        $this->product = $product;
        $customer = $this->currentCustomer();

        if ($customer) {
            // Store reviewed products
            $this->customerReviewedProductIds = ProductReview::where('customer_id', $customer->id)
                ->pluck('product_id')
                ->toArray();

            // Check if they purchased this product
            $this->hasPurchased = OrderItem::query()
                ->whereHas('order', function ($q) use ($customer) {
                    $q->where('customer_id', $customer->id)
                    ->where('status', ['paid', 'delivered', 'completed']);
                })
                ->where('product_id', $product->id)
                ->exists();
        }
    }

    protected function currentCustomer()
    {
        if ($customer = auth('customer')->user()) {
            return $customer;
        }

        if ($tenantUser = auth('web')->user()) {
            return $tenantUser->customers()->first();
        }

        return null;
    }

    public function getIsAdminProperty()
    {
        // Check if logged in user is a tenant dashboard user (admin)
        if ($tenantUser = auth('web')->user()) {
            // Use Spatie's hasRole method on tenantUser
            return $tenantUser->hasRole('admin');
        }

        // Frontend customers don't have admin roles
        return false;
    }

    public function toggleApproval($reviewId)
    {
        $review = ProductReview::findOrFail($reviewId);
        $review->is_approved = ! $review->is_approved;
        $review->save();

        session()->flash('review_message', 'Review moderation status updated.');
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $customer = $this->currentCustomer();

        if (!$customer || !$this->hasPurchased) {
            session()->flash('review_message', 'You must purchase this product to leave a review.');
            return;
        }

        $alreadyReviewed = ProductReview::where('product_id', $this->product->id)
            ->where('customer_id', $customer->id)
            ->exists();

        if ($alreadyReviewed) {
            session()->flash('review_message', 'You have already reviewed this product!');
            return;
        }

        ProductReview::create([
            'product_id' => $this->product->id,
            'customer_id' => $customer->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => true,
        ]);

        session()->flash('review_message', 'Thank you for your review!');

        $this->reset(['rating', 'comment']);

        $this->dispatch('updated_and_refresh');
    }

    public function startEditingReview(int $reviewId)
    {
        $review = ProductReview::where('id', $reviewId)
            ->where('customer_id', optional($this->currentCustomer())->id)
            ->firstOrFail();

        $this->editingReviewId = $review->id;
        $this->editingRating = $review->rating;
        $this->editingComment = $review->comment;
    }

    public function cancelEditing()
    {
        $this->reset(['editingReviewId', 'editingRating', 'editingComment']);
    }

    public function updateReview()
    {
        $this->validate([
            'editingRating' => 'required|integer|min:1|max:5',
            'editingComment' => 'nullable|string|max:2000',
        ]);

        $review = ProductReview::where('id', $this->editingReviewId)
            ->where('customer_id', optional($this->currentCustomer())->id)
            ->firstOrFail();

        $review->update([
            'rating' => $this->editingRating,
            'comment' => $this->editingComment,
        ]);

        session()->flash('review_message', 'Your review has been updated!');

        $this->reset(['editingReviewId', 'editingRating', 'editingComment']);
    }

    public function toggleReplies(int $reviewId)
    {
        if (in_array($reviewId, $this->showReplies)) {
            $this->showReplies = array_diff($this->showReplies, [$reviewId]);
        } else {
            $this->showReplies[] = $reviewId;
        }
    }

    public function render()
    {
        $reviews = $this->product->reviews()
            ->latest()
            ->paginate(10);

        return view('livewire.tenant.frontend.shopping.reviews.product-reviews', [
            'reviews' => $reviews,
            'currentCustomer' => $this->currentCustomer(),
        ]);
    }
}
