<div class="space-y-6">
    @php
        use Illuminate\Support\Facades\Auth;
        $tenantUser = Auth::guard('web')->user();
        $customerUser = Auth::guard('customer')->user();

        // If tenant user logged in and has linked customers, get first linked customer
        $linkedCustomer = null;
        if ($tenantUser && $tenantUser->relationLoaded('customers')) {
            $linkedCustomer = $tenantUser->customers->first();
        } elseif ($tenantUser) {
            // Lazy load if not eager loaded
            $linkedCustomer = $tenantUser->customers()->first();
        }
    @endphp
    @if ($customerUser || $linkedCustomer)

        @if (in_array($product->id, $customerReviewedProductIds))
            <p class="text-sm text-yellow-600 mb-6 font-semibold">You have already reviewed this product.</p>
        @else

            {{-- Review submission form --}}
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 text-gray-900">Leave a Review</h3>

                @if (session()->has('review_message'))
                    <div class="mb-4 text-green-700 font-medium">{{ session('review_message') }}</div>
                @endif

                <form wire:submit.prevent="submitReview" class="space-y-4">
                    {{-- Rating stars --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                        <div class="flex space-x-1 mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                        class="text-2xl focus:outline-none text-yellow-500 hover:text-yellow-600 transition">
                                    @if ($rating >= $i)
                                        &#9733;
                                    @else
                                        &#9734;
                                    @endif
                                </button>
                            @endfor
                        </div>
                        @error('rating') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Comment --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Comment (optional)</label>
                        <textarea wire:model.defer="comment" rows="3"
                                  class="w-full rounded border border-gray-300 focus:ring focus:ring-indigo-300 mt-2 text-gray-900"></textarea>
                        @error('comment') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Submit button --}}
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded transition-colors duration-300 font-semibold shadow-md">
                        Submit Review
                    </button>
                </form>
            </div>

        @endif
    @else
        <p class="text-sm text-gray-600">
            <a href="{{ route('shop.login') }}" class="underline text-indigo-600 hover:text-indigo-700 font-medium">
                Log in
            </a> to leave a review or reply.
        </p>
    @endif

    {{-- Approved Reviews List --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <h3 class="text-xl font-semibold mb-4 text-gray-900">Customer Reviews</h3>

        @forelse ($reviews as $review)
            <div class="border-b border-gray-300 py-4 last:border-0">
                {{-- Stars --}}
                <div class="text-yellow-500 text-lg">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($review->rating >= $i)
                            &#9733;
                        @else
                            &#9734;
                        @endif
                    @endfor
                </div>

                {{-- Comment --}}
                <div class="mt-2 text-gray-800">{{ $review->comment }}</div>

                {{-- Meta --}}
                <div class="mt-1 text-sm text-gray-500">
                    @if ($review->customer)
                        {{ $review->customer->name }}
                    @else
                        Anonymous
                        @endif
                        &bull;
                        {{ $review->created_at->diffForHumans() }}
                </div>

                {{-- Toggle replies button --}}
                <button wire:click="toggleReplies({{ $review->id }})"
                        class="text-indigo-600 hover:text-indigo-700 underline my-3 cursor-pointer text-sm font-medium">
                    {{ in_array($review->id, $showReplies) ? 'Hide Replies' : 'Show Replies' }}
                </button>

                {{-- Conditionally show replies --}}
                @if (in_array($review->id, $showReplies))
                    <livewire:tenant.frontend.shopping.reviews.review-replies :review="$review"
                                                                              :key="'review-'.$review->id"/>
                @endif
            </div>
        @empty
            <p class="text-gray-500">No reviews yet.</p>
        @endforelse

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
