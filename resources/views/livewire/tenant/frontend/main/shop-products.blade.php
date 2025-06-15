<div class="bg-black px-12 py-12">

    <div class="text-center">
        <h1 class="text-2xl font-bold mb-6">Available Products</h1>
    </div>

    @if (session('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 shadow-md" role="alert">
            {{ session('message') }}
        </div>
    @endif

    {{--<div
        x-data="{
            show: false,
            message: '',
            type: '',
        }"
        x-on:notify.window="
            message = $event.detail[0].message ?? '';
            type = $event.detail[0].type ?? 'info';
            show = true;
            setTimeout(() => show = false, 2000);
            "
        x-show="show"
        x-transition
        class=" my-5 px-4 py-3 rounded shadow-lg text-white"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-blue-600': type === 'info',
        }"
        style="display: none;"
    >
        <p x-text="message" class="text-sm font-semibold"></p>
    </div>--}}

    <div class="mb-6 bg-black rounded shadow">
        <h3 class="font-bold mb-3">Filter Products</h3>

        <div class="max-w-md mb-4">
            <label class="block font-semibold mb-1">Categories</label>
            <select
                wire:model.live.debounce.500ms="selectedCategories"
                multiple
                class="w-full border rounded p-2"
                size="4"
            >
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <small class="text-gray-500">Hold Ctrl to select or deselect multiple categories</small>
        </div>

        <div class="grid grid-cols-2 gap-4 max-w-xs">
            <div>
                <label class="block font-semibold mb-1" for="minPrice">Min Price (€)</label>
                <input
                    type="number"
                    wire:model.live.500ms="minPrice"
                    id="minPrice"
                    min="0"
                    step="0.01"
                    class="w-full border rounded p-2"
                    placeholder="0.00"
                />
            </div>
            <div>
                <label class="block font-semibold mb-1" for="maxPrice">Max Price (€)</label>
                <input
                    type="number"
                    wire:model.live.500ms="maxPrice"
                    id="maxPrice"
                    min="0"
                    step="0.01"
                    class="w-full border rounded p-2"
                    placeholder="0.00"
                />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-10">
        @forelse ($products as $product)
            <div    wire:key="product-{{ $product->id }}"
                    class="bg-black rounded-xl border border-gray-200 shadow p-4">
                @php
                    $image = $product->images->first();
                    $imageUrl = $image ? asset('tenant' . tenant()->id . '/' . $image->path) : 'https://placehold.co/200x200?text=No+Image';
                    $modalName = 'product-details-' . $product->id;
                @endphp
                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     class="w-full h-48 object-cover rounded">

                <h2 class="text-white text-lg font-semibold mt-4">{{ $product->name }}</h2>
                <p class="text-teal-600 font-bold">€{{ number_format($product->price, 2) }}</p>

                @if ($product->average_rating > 0)
                    <div class="flex items-center text-yellow-400 text-sm mt-1">
                        ★ {{ number_format($product->average_rating, 1) }}
                        <span class="text-gray-400 text-xs ms-2">({{ $product->approved_reviews_count }} reviews)</span>
                    </div>
                @else
                    <div class="flex items-center text-yellow-400 text-sm mt-1">
                        ☆ 0
                        <span class="text-gray-400 text-xs ms-2">({{ $product->approved_reviews_count }} reviews)</span>
                    </div>
                @endif

                <div class="mt-5 flex justify-between">
                    <div class="flex flex-col gap-4">
                        <flux:modal.trigger name="product-details-{{ $product->id }}">
                            <flux:button class="cursor-pointer transition-colors duration-300 ease-in-out">
                                Quick View
                            </flux:button>
                        </flux:modal.trigger>

                        <flux:modal name="product-details-{{ $product->id }}" class="md:w-full">
                            <div>
                                <div
                                    x-data="{ show: false, message: '', type: '' }"
                                    x-on:notify.window="message = $event.detail.message; type = $event.detail.type; show = true; setTimeout(() => show = false, 3000)"
                                    x-show="show"
                                    x-text="message"
                                    :class="{
                                    'bg-green-500 text-white': type === 'success',
                                    'bg-red-500 text-white': type === 'error'
                                }"
                                    class="fixed top-4 right-4 p-3 rounded shadow"
                                    style="display: none;"
                                ></div>

                                <flux:heading size="xl">{{ $product->name }}</flux:heading>
                                <flux:text class="text-sm text-gray-300 mt-3">{{ $product->description }}</flux:text>

                                <div class="flex flex-col mt-5">
                                    <div>
                                        <p><strong>Price:</strong> €{{ number_format($product->price, 2) }}</p>
                                        <p>
                                            <strong>Categories:</strong> {{ $product->categories->implode('name', ', ') }}
                                        </p>
                                        <p class="text-green-600"><strong>Stock:</strong> {{ $product->stock }}</p>
                                        @if ($product->average_rating > 0)
                                            <div class="flex items-center text-yellow-400 text-sm mt-1">
                                                ★ {{ number_format($product->average_rating, 1) . ' ' . '/' . ' ' . '5' }}
                                                <span class="text-gray-400 text-xs ms-2">({{ $product->approved_reviews_count }} reviews)</span>
                                            </div>
                                        @else
                                            <div class="flex items-center text-yellow-400 text-sm mt-1">
                                                ☆ 0
                                                <span class="text-gray-400 text-xs ms-2">({{ $product->approved_reviews_count }} reviews)</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 mt-5">
                                        @foreach ($product->images as $img)
                                            @if ($img->product_variant_id == null)
                                                <img src="{{ asset('tenant' . tenant()->id . '/' . $img->path) }}"
                                                     alt="Image"
                                                     class="w-48 h-48 object-cover rounded mb-2"
                                                >
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                @if ($product->variants->count())
                                    <div class="mt-5">
                                        <flux:heading size="sm">Variants</flux:heading>
                                        <ul class="list-disc list-inside text-sm text-gray-300">
                                            @foreach ($product->variants as $variant)
                                                <li class="flex justify-between my-3">
                                                    <div class="flex me-4">
                                                        <div>
                                                            <img
                                                                src="{{ asset('tenant' . tenant()->id . '/' . $variant->images->first()->path) }}"
                                                                alt="Variant Image"
                                                                class="w-16 h-16 object-cover rounded mr-2"
                                                            >
                                                        </div>
                                                        <div class="flex items-center">
                                                            @if ($variant->stock > 0)
                                                                <div>
                                                                    <p>{{ $variant->name }} -
                                                                        €{{ number_format($variant->price, 2) }}</p>
                                                                    <p class="text-green-600">In
                                                                        Stock: {{ $variant->stock }}</p>
                                                                </div>
                                                            @else
                                                                <p class="text-red-600">Out of Stock</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @php
                                                        // Check stock level for main product or variant
                                                        $isOutOfStock = isset($variant)
                                                            ? $variant->stock <= 0
                                                            : $product->stock <= 0;
                                                    @endphp

                                                    <div class="flex justify-end items-center">
                                                        <form method="POST" action="{{ route('shop.add-to-cart') }}">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                            <input type="hidden" name="variant_id" value="{{ $variant->id ?? '' }}">

                                                            <button
                                                                type="submit"
                                                                @if ($isOutOfStock) disabled @endif
                                                                class="btn px-4 py-2 rounded-md
                                                                    {{ $isOutOfStock ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-700' }}
                                                                    transition-colors duration-300 cursor-pointer"
                                                            >
                                                                Add to Cart
                                                            </button>
                                                        </form>
                                                    </div>

                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{--Product Reviews Section--}}
                                <div class="mt-8 border-t pt-6">
                                    {{--<livewire:tenant.frontend.shopping.reviews.product-reviews :product="$product"
                                                                                               :reviews="$product->reviews"
                                                                                               :hasReviewed="in_array($product->id, $customerReviewedProductIds)"
                                                                                               :key="'reviews-' . $product->id"
                                    />--}}
                                    @auth('customer')

                                        @if (in_array($product->id, $customerReviewedProductIds))
                                            <p class="text-sm text-yellow-400 mb-6">You have already reviewed this
                                                product.</p>
                                        @else

                                            {{-- Review submission form --}}
                                            <div class="bg-black p-6 rounded-xl shadow">
                                                <h3 class="text-lg font-semibold mb-4">Leave a Review</h3>

                                                @if (session()->has('message'))
                                                    <div class="mb-4 text-green-600">{{ session('message') }}</div>
                                                @endif

                                                <form wire:submit.prevent="submitReview({{ $product->id }})"
                                                      class="space-y-4">
                                                    {{-- Rating stars --}}
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700">Rating</label>
                                                        <div class="flex space-x-1 mt-1">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <button type="button"
                                                                        wire:click="$set('rating', {{ $i }})"
                                                                        class="text-xl focus:outline-none">
                                                                    @if ($rating >= $i)
                                                                        ⭐
                                                                    @else
                                                                        ☆
                                                                    @endif
                                                                </button>
                                                            @endfor
                                                        </div>
                                                        @error('rating') <span
                                                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- Comment --}}
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Comment
                                                            (optional)</label>
                                                        <textarea wire:model.defer="comment" rows="3"
                                                                  class="w-full rounded border-gray-300 focus:ring focus:ring-indigo-200 mt-5"></textarea>
                                                        @error('comment') <span
                                                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- Submit button --}}
                                                    <button type="submit"
                                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition-colors duration-300 cursor-pointer">
                                                        Submit Review
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-400">
                                            <a href="{{ route('shop.customer-login') }}"
                                               class="underline text-blue-500">Log in</a> to leave a
                                            review or reply.
                                        </p>
                                    @endauth

                                    {{-- Approved Reviews List --}}
                                    <div class="bg-black p-6 mt-6 rounded-xl shadow">
                                        <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>

                                        @forelse ($product->reviews as $review)
                                            <div class="border-b py-4">
                                                {{-- Stars --}}
                                                <div class="text-yellow-500">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($review->rating >= $i)
                                                            ⭐
                                                        @else
                                                            ☆
                                                        @endif
                                                    @endfor
                                                </div>

                                                {{-- Comment --}}
                                                <div class="mt-2 text-gray-300">{{ $review->comment }}</div>

                                                {{-- Meta --}}
                                                <div class="mt-1 text-sm text-gray-500">
                                                    @if ($review->customer)
                                                        {{ $review->customer->name }}
                                                    @else
                                                        Anonymous
                                                        @endif
                                                        &bullet;
                                                        {{ $review->created_at->diffForHumans() }}
                                                </div>
                                                {{-- Toggle replies button --}}
                                                <button wire:click="toggleReplies({{ $review->id }})"
                                                        class="text-blue-500 underline my-3 cursor-pointer">
                                                    {{ in_array($review->id, $showReplies) ? 'Hide Replies' : 'Show Replies' }}
                                                </button>

                                                {{-- Conditionally show replies --}}
                                                @if(in_array($review->id, $showReplies))
                                                    <h4 class="text-sm font-semibold text-gray-300 mb-3">Replies</h4>

                                                    @foreach($replies[$review->id] ?? [] as $reply)
                                                        <div
                                                            class="text-sm text-gray-200 mb-2 border-l border-gray-700 pl-3">
                                                            <p class="mb-1">
                                                                <strong>{{ $reply->customer?->name ?? 'Deleted User' }}</strong>
                                                                <span class="text-xs text-gray-500 ml-2">
                                                                    {{ $reply->created_at->diffForHumans() }}
                                                                </span>
                                                            </p>
                                                            <p class="text-gray-400">{{ $reply->body }}</p>
                                                        </div>
                                                    @endforeach

                                                    @auth('customer')
                                                        <form wire:submit.prevent="submitReply({{ $review->id }})"
                                                              class="mt-3 space-y-2">
                                                            @csrf
                                                            <textarea wire:model.defer="replyBodies.{{ $review->id }}"
                                                                      class="w-full rounded bg-gray-900 text-white border-gray-700 focus:ring focus:ring-indigo-400"
                                                                      rows="2"
                                                                      placeholder="Write a reply..."></textarea>
                                                            @error("replyBodies.$review->id")
                                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                                            @enderror

                                                            <button type="submit"
                                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm cursor-pointer">
                                                                Reply
                                                            </button>
                                                        </form>

                                                        @if (session()->has('review_message'))
                                                            <p class="text-green-400 text-sm mt-2">{{ session('review_message') }}</p>
                                                        @endif
                                                    @endauth
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

                                <div class="flex justify-end">
                                    <flux:button
                                        variant="primary"
                                        x-on:click="$flux.modal('{{ $modalName }}').close()"
                                        class="mt-6 cursor-pointer transition-colors duration-300"
                                    >
                                        Close
                                    </flux:button>
                                </div>
                            </div>
                        </flux:modal>

                        <div>
                            <a
                                class="btn px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-700 transition-colors duration-300 cursor-pointer"
                                href="{{ route('shop.shop-product-show', $product->slug) }}"
                            >
                                View Details
                            </a>
                        </div>
                    </div>

                    {{--<livewire:tenant.frontend.shopping.add-to-cart-button :product="$product" />--}}
                    <div class="flex items-center">
                        <form method="POST" action="{{ route('shop.add-to-cart') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn px-4 py-2 rounded-md bg-green-500 hover:bg-green-700 transition-colors duration-300 cursor-pointer">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>No products matching your search criteria.</p>
        @endforelse
    </div>
</div>
