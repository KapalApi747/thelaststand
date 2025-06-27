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
            $linkedCustomer = $tenantUser->customers()->first();
        }
    @endphp

    @if ($customerUser || $linkedCustomer)

        @if (! $hasPurchased)
            <p class="text-sm text-gray-500 italic mb-4">Only verified buyers can leave a review.</p>
        @elseif (in_array($product->id, $customerReviewedProductIds))
            <p class="text-sm text-yellow-600 mb-6 font-semibold">You have already reviewed this product.</p>
        @else
            {{-- Review submission form --}}
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 text-gray-900">Leave a Review</h3>

                <form wire:submit.prevent="submitReview" class="space-y-4">
                    @csrf
                    {{-- Rating stars --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                        <div class="flex space-x-1 mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                        class="text-2xl focus:outline-none text-yellow-500 hover:text-yellow-600 transition">
                                    {!! $rating >= $i ? '&#9733;' : '&#9734;' !!}
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
                    <div>
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded transition-colors duration-300 font-semibold shadow-md cursor-pointer">
                            Submit Review
                        </button>
                    </div>

                </form>
            </div>
        @endif

    @else
        <p class="text-sm text-gray-600">
            <a href="{{ route('login') }}" class="underline text-indigo-600 hover:text-indigo-700 font-medium">
                Log in
            </a> to leave a review or reply.
        </p>
    @endif

    {{--
        Beoordelingen van klanten (alleen goedgekeurde reviews)
        Deze container gebruikt Alpine.js om automatisch naar het review-gedeelte te scrollen
        wanneer de paginaparameter ('?page=...') verandert – handig bij paginatie.
    --}}
    <div
        id="customer-reviews"
        x-data="{ page: new URLSearchParams(window.location.search).get('page') }"
        x-init="
        $watch('page', value => {
            const el = document.getElementById('customer-reviews');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        // Listen to URL changes
        const observer = new MutationObserver(() => {
            const newPage = new URLSearchParams(window.location.search).get('page');
            if (newPage !== page) {
                page = newPage;
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });
    "
        class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
    >
        <h3 class="text-xl font-semibold mb-4 text-gray-900">Customer Reviews</h3>

        @if (session()->has('review_message'))
            <div class="mb-4 text-green-700 font-medium">{{ session('review_message') }}</div>
        @endif

        @forelse ($reviews as $review)
            <div class="border-b border-gray-300 py-4 last:border-0">

                @if ($editingReviewId === $review->id)
                    {{-- Inline Edit Form --}}
                    <form wire:submit.prevent="updateReview" class="space-y-3">
                        {{-- Rating --}}
                        <div class="text-yellow-500 text-lg flex space-x-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('editingRating', {{ $i }})"
                                        class="focus:outline-none">
                                    {!! $editingRating >= $i ? '&#9733;' : '&#9734;' !!}
                                </button>
                            @endfor
                        </div>
                        @error('editingRating') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                        {{-- Comment --}}
                        <div>
                        <textarea wire:model.defer="editingComment" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            @error('editingComment') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex space-x-2">
                            <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800 transition-colors duration-300 cursor-pointer">
                                Save
                            </button>
                            <button type="button"
                                    wire:click="cancelEditing"
                                    class="text-gray-600 hover:underline hover:text-gray-800 transition-colors duration-300 cursor-pointer">
                                Cancel
                            </button>
                        </div>
                    </form>
                @else
                    {{-- Static Review Display --}}

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
                    @if (! $review->is_approved)
                        <div class="mt-2 text-gray-500 line-through italic">
                            This review has been removed by a moderator.
                        </div>
                    @else
                        <div class="mt-2 text-gray-800">{{ $review->comment }}</div>
                    @endif

                    {{-- Meta --}}
                    <div class="flex">
                        <div class="mt-1 text-sm text-gray-500">
                            @if ($review->customer)
                                {{ $review->customer->name }}
                            @else
                                Anonymous
                                @endif
                                &bull;
                                {{ $review->created_at->diffForHumans() }}
                        </div>
                        {{-- Edit Button (for current customer only) --}}
                        @if ($currentCustomer && $review->customer_id === $currentCustomer->id)
                            <div class="ms-3">
                                <button wire:click="startEditingReview({{ $review->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-1 cursor-pointer transition-colors duration-300">
                                    Edit Review
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Toggle replies button --}}
                <button wire:click="toggleReplies({{ $review->id }})"
                        class="text-indigo-600 hover:text-indigo-700 underline my-3 cursor-pointer text-sm font-medium block">
                    {{ in_array($review->id, $showReplies) ? 'Hide Replies' : 'Show Replies' }}
                </button>

                    @if ($this->isAdmin)
                        <button
                            wire:click="toggleApproval({{ $review->id }})"
                            class="text-red-600 hover:text-red-400 underline my-3 cursor-pointer text-sm font-medium block"
                        >
                            {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                        </button>
                    @endif

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
