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

    public function render()
    {
        $reviews = ProductReview::where('customer_id', $this->customer->id)
            ->latest()
            ->paginate(5);

        $replies = ProductReviewReply::where('customer_id', $this->customer->id)
            ->latest()
            ->paginate(5);

        return view('livewire.tenant.backend.customers.customer-view', [
            'reviews' => $reviews,
            'replies' => $replies,
        ]);
    }
}
