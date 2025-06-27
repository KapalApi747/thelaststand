<div class="bg-gray-50 min-h-screen px-8 py-12 text-gray-800">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Available Products</h1>
    </div>

    @if (session('message'))
        <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 shadow" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 sm:gap-y-6 lg:gap-6">
        <!-- Filters -->
        <aside class="bg-white border border-gray-200 p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Filter Products</h3>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2 text-gray-700" for="search">Search Products</label>
                <input
                    type="text"
                    id="search"
                    wire:model.live.300ms="search"
                    placeholder="Search by name..."
                    class="w-full rounded-md border border-gray-300 text-gray-800 p-2 bg-white"
                />
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2 text-gray-700">Categories</label>

                <div class="flex flex-col space-y-2">
                    @foreach ($categories->where('parent_id', null) as $category)
                        @include('partials.categories.category-checkbox', ['category' => $category, 'level' => 0])
                    @endforeach
                </div>

                <small class="text-gray-500 mt-1 block">Select one or more categories</small>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700" for="minPrice">Min Price (€)</label>
                    <input
                        type="number"
                        wire:model.live.500ms="minPrice"
                        id="minPrice"
                        min="0"
                        step="0.01"
                        class="w-full rounded-md border border-gray-300 text-gray-800 p-2 bg-white"
                        placeholder="0.00"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700" for="maxPrice">Max Price (€)</label>
                    <input
                        type="number"
                        wire:model.live.500ms="maxPrice"
                        id="maxPrice"
                        min="0"
                        step="0.01"
                        class="w-full rounded-md border border-gray-300 text-gray-800 p-2 bg-white"
                        placeholder="0.00"
                    />
                </div>
            </div>
        </aside>

        <!-- Product Cards -->
        <main class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($products as $product)
                @php
                    $mainProductOutOfStock = $product->stock <= 0;
                    $image = $product->images->first();
                    $imageUrl = $image ? asset('tenant' . tenant()->id . '/' . $image->path) : 'https://placehold.co/200x200?text=No+Image';
                    $modalName = 'product-details-' . $product->id;

                    $avgRating = $product->approved_reviews_avg_rating;
                    $count = $product->approved_reviews_count;
                @endphp

                <div wire:key="product-{{ $product->id }}"
                     class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col justify-between">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                         class="w-full h-48 object-cover rounded mb-4">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h2>
                        <p class="font-bold {{ $mainProductOutOfStock ? 'line-through text-gray-400' : 'text-teal-600' }}">
                            €{{ number_format($product->price, 2) }}
                        </p>

                        <div class="flex items-center text-yellow-500 text-sm mt-1">

                            @if ($avgRating > 0)
                                ★ {{ number_format($avgRating, 1) }}
                            @else
                                ☆ 0
                            @endif

                            <span class="text-gray-500 text-xs ml-2">({{ $count }} reviews)</span>
                        </div>

                    </div>

                    <div class="mt-4 flex flex-col space-y-3">
                        <div class="flex justify-between items-center">
                            <flux:modal.trigger name="{{ $modalName }}">
                                <flux:button
                                    class="cursor-pointer transition-colors duration-300"
                                >
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
                                            <p class="text-green-600 {{ $product->stock > 0 ? '' : 'text-red-600' }}"><strong>Stock:</strong> {{ $product->stock }}</p>
                                            <div class="flex items-center text-yellow-500 text-sm mt-1">

                                                @if ($avgRating > 0)
                                                    ★ {{ number_format($avgRating, 1) }}
                                                @else
                                                    ☆ 0
                                                @endif

                                                <span class="text-gray-500 text-xs ml-2">({{ $count }} reviews)</span>
                                            </div>
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
                                                                    <div>
                                                                        <p>{{ $variant->name }} -
                                                                            €{{ number_format($variant->price, 2) }}</p>
                                                                        <p class="text-red-600">Out of Stock</p>
                                                                    </div>
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
                                                                    class="btn px-4 py-2 rounded-md text-gray-100
                                                                    {{ $isOutOfStock ? 'bg-gray-400 disabled:opacity-50 disabled:cursor-not-allowed' : 'bg-green-500 hover:bg-green-700' }}
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

                            <a href="{{ route('shop.shop-product-show', $product->slug) }}"
                               class="px-4 py-2 bg-blue-400 hover:bg-blue-200 text-white rounded-md transition-colors duration-300">
                                View Details
                            </a>
                        </div>

                        <div class="text-center text-sm">
                            @if ($mainProductOutOfStock)
                                <span class="text-red-500 font-semibold">Out of Stock</span>
                            @else
                                <span class="text-green-600 font-semibold">In Stock</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('shop.add-to-cart') }}" class="text-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button
                                type="submit"
                                @if ($mainProductOutOfStock) disabled @endif
                                class="w-full text-sm px-4 py-2 rounded-md
                                    {{ $mainProductOutOfStock
                                        ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                        : 'bg-green-500 hover:bg-green-600 text-white cursor-pointer' }}
                                    transition-colors duration-300"
                            >
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">No products matching your search criteria.</p>
            @endforelse
        </main>
    </div>
    <div class="mt-6 w-full">
        {{ $products->links() }}
    </div>
</div>
