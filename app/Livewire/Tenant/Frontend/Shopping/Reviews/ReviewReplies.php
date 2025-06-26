<?php

namespace App\Livewire\Tenant\Frontend\Shopping\Reviews;

use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use Livewire\Component;

class ReviewReplies extends Component
{
    public ProductReview $review;
    public string $body = '';
    public $replyCount;

    public function mount()
    {
        $this->replyCount = $this->review->replies()->count();
    }

    protected $rules = [
        'body' => 'required|string|max:1000',
    ];

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

    public function toggleApproval($reviewReplyId)
    {
        $reviewReply = ProductReviewReply::findOrFail($reviewReplyId);
        $reviewReply->is_approved = ! $reviewReply->is_approved;
        $reviewReply->save();

        session()->flash('message', 'Reply moderation status updated.');
    }

    public function submit()
    {
        $this->validate();

        $customer = $this->currentCustomer();

        ProductReviewReply::create([
            'product_review_id' => $this->review->id,
            'customer_id' => $customer->id,
            'body' => $this->body,
        ]);

        $this->reset('body');
        $this->replyCount = $this->review->replies()->count();

        $this->review->refresh();
        session()->flash('message', 'Your reply has been submitted!');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.reviews.review-replies', [
            'replies' => $this->review->replies()->latest()->get(),
        ]);
    }
}
