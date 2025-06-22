<?php

namespace App\Livewire\Tenant\Backend\Customers;

use App\Models\Customer;
use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('t-dashboard-layout')]
class CustomerView extends Component
{
    use WithPagination;

    public Customer $customer;

    public $reviewsPage = 1;
    public $repliesPage = 1;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load([
            'orders',
            'addresses',
        ]);
    }

    public function toggleReviewApproval($reviewId)
    {
        $review = $this->customer->reviews()->findOrFail($reviewId);
        $review->is_approved = !$review->is_approved;
        $review->save();
    }

    public function toggleReplyApproval($replyId)
    {
        $reply = ProductReviewReply::findOrFail($replyId);
        $reply->is_approved = ! $reply->is_approved;
        $reply->save();
    }

    public function updatingReviewsPage()
    {
        // Reset replies page to 1 only if it's not already 1
        if ($this->repliesPage !== 1) {
            $this->repliesPage = 1;
        }
    }

    public function updatingRepliesPage()
    {
        // Reset reviews page to 1 only if it's not already 1
        if ($this->reviewsPage !== 1) {
            $this->reviewsPage = 1;
        }
    }

    public function render()
    {
        $reviews = ProductReview::where('customer_id', $this->customer->id)
            ->latest()
            ->paginate(5, ['*'], 'reviewsPage');

        $replies = ProductReviewReply::where('customer_id', $this->customer->id)
            ->latest()
            ->paginate(10, ['*'], 'repliesPage');

        return view('livewire.tenant.backend.customers.customer-view', [
            'reviews' => $reviews,
            'replies' => $replies,
        ]);
    }
}
