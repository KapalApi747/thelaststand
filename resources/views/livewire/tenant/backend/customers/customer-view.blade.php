<div class="p-6 bg-white rounded shadow">
    <div>
        <h3 class="h3 font-semibold mb-6">Customer Details: {{ $customer->name }}</h3>

        <section class="mb-6">
            <h3 class="font-semibold text-lg mb-2">Basic Information</h3>
            <p class="text-gray-800"><strong>Email:</strong> {{ $customer->email }}</p>
            <p class="text-gray-800"><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p class="text-gray-800"><strong>Status:</strong> {{ $customer->is_active ? 'Active' : 'Inactive' }}</p>
            <p class="text-gray-800"><strong>Joined At:</strong> {{ optional($customer->created_at)->format('Y-m-d') }}
            </p>
        </section>

        <section class="mb-6">
            <h3 class="font-semibold text-lg mb-2">Order Summary</h3>
            <p class="text-gray-800"><strong>Total Orders:</strong> {{ $customer->orders->count() }}</p>
            <p class="text-gray-800"><strong>Total Spent:</strong>
                €{{ number_format($customer->orders->sum('total_amount'), 2) }}</p>
        </section>

        <section class="mb-6 grid grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-lg mb-2">Shipping Address</h3>
                @if($shipping = $customer->addresses->firstWhere('type', 'shipping'))
                    <p class="text-gray-800">{{ $shipping->address_line1 }}</p>
                    <p class="text-gray-800">{{ $shipping->address_line2 }}</p>
                    <p class="text-gray-800">{{ $shipping->city }}, {{ $shipping->state }} {{ $shipping->zip }}</p>
                    <p class="text-gray-800">{{ $shipping->country }}</p>
                @else
                    <p class="text-gray-800">No shipping address set.</p>
                @endif
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-2">Billing Address</h3>
                @if($billing = $customer->addresses->firstWhere('type', 'billing'))
                    <p class="text-gray-800">{{ $billing->address_line1 }}</p>
                    <p class="text-gray-800">{{ $billing->address_line2 }}</p>
                    <p class="text-gray-800">{{ $billing->city }}, {{ $billing->state }} {{ $billing->zip }}</p>
                    <p class="text-gray-800">{{ $billing->country }}</p>
                @else
                    <p class="text-gray-800">No billing address set.</p>
                @endif
            </div>
        </section>

        <section id="reviews">
            <h3 class="font-semibold text-lg mb-2">Review History</h3>
            @if($reviews->isEmpty())
                <p class="text-gray-800">No reviews written.</p>
            @else
                <ul class="space-y-3 max-h-48 overflow-y-auto">
                    @foreach($reviews as $review)
                        <li class="border rounded p-4 flex justify-between items-start">
                            <div>
                                <p class="text-gray-800"><strong>Product ID:</strong> {{ $review->product_id }}</p>
                                <p class="text-gray-800"><strong>Rating:</strong> {{ $review->rating }}/5</p>
                                <p class="text-gray-800"><strong>Comment:</strong> {{ $review->comment }}</p>
                                <p class="text-sm text-gray-600">
                                    Reviewed on {{ optional($review->created_at)->format('Y-m-d') }}
                                </p>
                            </div>

                            <div class="ml-4">
                                <button
                                    wire:click="toggleReviewApproval({{ $review->id }})"
                                    class="px-3 py-1 text-sm rounded bg-{{ $review->is_approved ? 'green' : 'yellow' }}-100 text-{{ $review->is_approved ? 'green' : 'yellow' }}-800 hover:bg-{{ $review->is_approved ? 'green' : 'yellow' }}-200 transition"
                                >
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6">
                    {{ $reviews->links('pagination::tailwind', ['pageName' => 'reviewsPage']) }}
                </div>
            @endif
        </section>

        <section id="review-replies" class="mt-12">
            <h3 class="font-semibold text-lg mb-2">Review Replies</h3>

            @if($replies->isEmpty())
                <p class="text-gray-800">No replies written.</p>
            @else
                <ul class="space-y-3">
                    @foreach($replies as $reply)
                        <li class="border rounded p-4 flex justify-between items-start">
                            <div>
                                <p class="text-gray-800"><strong>Reply:</strong> {{ $reply->body }}</p>
                                <p class="text-gray-800"><strong>To Review ID:</strong> {{ $reply->product_review_id }}</p>
                                <p class="text-sm text-gray-600">Replied on {{ optional($reply->created_at)->format('Y-m-d') }}</p>
                            </div>

                            <div class="ml-4">
                                <button
                                    wire:click="toggleReplyApproval({{ $reply->id }})"
                                    class="px-3 py-1 text-sm rounded bg-{{ $reply->is_approved ? 'green' : 'yellow' }}-100 text-{{ $reply->is_approved ? 'green' : 'yellow' }}-800 hover:bg-{{ $reply->is_approved ? 'green' : 'yellow' }}-200 transition"
                                >
                                    {{ $reply->is_approved ? 'Approved' : 'Pending' }}
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6">
                    {{ $replies->links('pagination::tailwind', ['pageName' => 'repliesPage']) }}
                </div>
            @endif
        </section>


        <div class="mt-6">
            <a href="{{ route('tenant-dashboard.customer-index') }}" class="btn btn-primary">← Back to customers
                list</a>
        </div>
    </div>
</div>
