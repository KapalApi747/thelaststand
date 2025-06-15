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

    public function submit()
    {
        $this->validate();

        ProductReviewReply::create([
            'product_review_id' => $this->review->id,
            'customer_id' => auth('customer')->id(),
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
