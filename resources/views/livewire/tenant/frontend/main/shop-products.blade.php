<div class="bg-black p-12">
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-6">Available Products</h1>
    </div>
    <div class="flex justify-between items-center">
        <div>
            @auth('customer')
                <p class="text-green-300 font-medium">
                    Hello, {{ auth('customer')->user()->name }}!
                </p>
            @endauth
        </div>
        <div class="flex items-center">
            <div class="me-6">
                <a
                    class="bg-teal-600 text-white py-2 px-4 rounded hover:bg-teal-700 transition"
                    href="{{ route('shop.shop-cart') }}"
                >
                    Shopping Cart
                </a>
            </div>
            @auth('customer')
                <div>
                    <form method="POST" action="{{ route('shop.customer-logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div>
                    <a
                        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-teal-700 transition"
                        href="{{ route('shop.customer-login') }}"
                    >
                        Login
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <div
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
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-10">
        @forelse ($products as $product)
            <div class="bg-white rounded-xl shadow p-4">
                @php
                    $image = $product->images->first();
                    $imageUrl = $image ? asset('tenant' . tenant()->id . '/' . $image->path) : 'https://placehold.co/200x200?text=No+Image';
                    $modalName = 'product-details-' . $product->id;
                @endphp
                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     class="w-full h-48 object-cover rounded">

                <h2 class="text-black text-lg font-semibold mt-4">{{ $product->name }}</h2>
                <p class="text-teal-600 font-bold">€{{ number_format($product->price, 2) }}</p>

                @if ($product->average_rating > 0)
                    <div class="flex items-center text-yellow-400 text-sm mt-1">
                        ★ {{ number_format($product->average_rating, 1) }}
                        <span class="text-gray-400 text-xs ms-2">({{ $product->rating_count }} reviews)</span>
                    </div>
                @else
                    <div class="flex items-center text-yellow-400 text-sm mt-1">
                        ☆ 0
                        <span class="text-gray-400 text-xs ms-2">({{ $product->rating_count }} reviews)</span>
                    </div>
                @endif

                <div class="mt-5">
                    <flux:modal.trigger name="product-details-{{ $product->id }}">
                        <flux:button variant="primary">
                            View Details
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

                            <flux:heading size="lg">{{ $product->name }}</flux:heading>
                            <flux:text class="text-sm text-gray-300">{{ $product->description }}</flux:text>

                            <div class="flex flex-col mt-5">
                                <div>
                                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                                    <p><strong>Price:</strong> €{{ number_format($product->price, 2) }}</p>
                                    <p><strong>Categories:</strong> {{ $product->categories->implode('name', ', ') }}</p>
                                    <p class="text-green-600"><strong>Stock:</strong> {{ $product->stock }}</p>
                                    @if ($product->average_rating > 0)
                                        <div class="flex items-center text-yellow-400 text-sm mt-1">
                                            ★ {{ number_format($product->average_rating, 1) . ' ' . '/' . ' ' . '5' }}
                                            <span class="text-gray-400 text-xs ms-2">({{ $product->rating_count }} reviews)</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-yellow-400 text-sm mt-1">
                                            ☆ 0
                                            <span class="text-gray-400 text-xs ms-2">({{ $product->rating_count }} reviews)</span>
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
                                                                <p>{{ $variant->name }} - €{{ number_format($variant->price, 2) }}</p>
                                                                <p class="text-green-600">In Stock: {{ $variant->stock }}</p>
                                                            </div>
                                                        @else
                                                            <p class="text-red-600">Out of Stock</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center">
                                                    <livewire:tenant.frontend.shopping.add-to-cart-button
                                                        :product="$product"
                                                        :variant="$variant"
                                                        :key=" 'variant-' . $variant->id"
                                                    />
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Product Reviews Section --}}
                            <div class="mt-8 border-t pt-6">
                                <livewire:tenant.frontend.shopping.reviews.product-reviews :product="$product" :key="'reviews-' . $product->id" />
                            </div>

                            <div class="flex justify-end">
                                <flux:button
                                    variant="primary"
                                    x-on:click="$flux.modal('{{ $modalName }}').close()"
                                    class="mt-6"
                                >
                                    Close
                                </flux:button>
                            </div>
                        </div>
                    </flux:modal>

                    <livewire:tenant.frontend.shopping.add-to-cart-button :product="$product" />
                </div>
            </div>
        @empty
            <p>No products available.</p>
        @endforelse
    </div>
</div>
