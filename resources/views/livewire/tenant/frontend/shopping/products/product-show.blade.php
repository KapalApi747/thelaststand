<div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-black p-4 sm:p-6 md:p-12">
    {{-- Image gallery --}}
    <div
        x-data="{ mainImage: '' }"
        x-init="
        mainImage = '{{ asset('tenant' . tenant()->id . '/' . ($product->mainImage?->path ?? 'https://placehold.co/200x200?text=No+Image')) }}'
    "
        x-on:variant-image-updated.window="mainImage = $event.detail[0].url"
    >
        {{-- Main Image --}}
        <img
            :src="mainImage"
            alt="{{ $product->name }}"
            class="w-full h-64 sm:h-80 md:h-96 object-cover rounded-xl transition duration-300 ease-in-out"
        >

        {{-- Main Product Images Thumbnails --}}
        <div class="mt-4">
            <h3 class="text-white font-semibold mb-2">Main Product Images</h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($product->images->where('product_variant_id', null) as $image)
                    <img
                        src="{{ asset('tenant' . tenant()->id . '/' . $image->path) }}"
                        @click="mainImage = '{{ asset('tenant' . tenant()->id . '/' . $image->path) }}'"
                        class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded border cursor-pointer transition-all hover:ring-2 hover:ring-white"
                    >
                @endforeach
            </div>
        </div>

        {{-- Variant Images Thumbnails --}}
        @if ($product->variants->count())
            @foreach ($product->variants as $variant)
                <div class="mt-6">
                    <h3 class="text-white font-semibold mb-2">Image(s) for {{ $product->name }} - {{ $variant->name }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($variant->images as $variantImage)
                            <img
                                src="{{ asset('tenant' . tenant()->id . '/' . $variantImage->path) }}"
                                @click="mainImage = '{{ asset('tenant' . tenant()->id . '/' . $variantImage->path) }}'"
                                class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded border cursor-pointer transition-all hover:ring-2 hover:ring-white"
                            >
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Product Info --}}
    <div class="flex flex-col justify-between">
        <div>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $product->name }}</h1>

                {{-- Back Button --}}
                <a class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-300 text-center"
                   href="{{ route('shop.shop-products') }}">
                    Back
                </a>
            </div>

            {{-- Price --}}
            <p class="text-lg sm:text-xl text-gray-200 mt-3">
                €{{ number_format($selected?->price ?? $product->price, 2) }}
            </p>

            {{-- Stock --}}
            @php $stock = $selected?->stock ?? $product->stock; @endphp
            <p class="{{ $stock > 0 ? 'text-green-500' : 'text-red-500' }} mt-1">
                {{ $stock > 0 ? 'In Stock' : 'Out of Stock' }} ({{ $stock }})
            </p>

            {{-- Description --}}
            <p class="mt-4 text-gray-200 text-sm sm:text-base">{{ $product->description }}</p>

            {{-- Categories --}}
            <p class="mt-4 text-gray-200 text-sm sm:text-base">
                <strong>Categories:</strong> {{ $product->categories->implode('name', ', ') }}
            </p>

            {{-- Variant Selector --}}
            @if ($product->variants->isNotEmpty())
                <div class="mt-6">
                    <h3 class="font-semibold mb-2 text-white text-sm sm:text-base">Choose a Variant (optional):</h3>
                    <select wire:model.live="variantId" class="w-full rounded border-gray-300 bg-black text-white text-sm">
                        <option value="{{ null }}">-- Standard --</option>
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}">
                                {{ $variant->name }} - €{{ number_format($variant->price, 2) }}
                                ({{ $variant->stock }} in stock)
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        {{-- Add to Cart --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mt-6">
            <form method="POST" action="{{ route('shop.add-to-cart') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @if ($variantId)
                    <input type="hidden" name="variant_id" value="{{ $variantId }}">
                @endif

                <button
                    type="submit"
                    class="btn w-full sm:w-auto px-4 py-2 rounded-md bg-green-500 hover:bg-green-700 transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ $stock <= 0 ? 'disabled' : '' }}
                >
                    Add to Cart
                </button>
            </form>

            @if ($stock <= 0)
                <p class="text-red-500 font-semibold">Out of Stock</p>
            @endif
        </div>

        @if (session('message'))
            <div class="my-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 shadow-md" role="alert">
                {{ session('message') }}
            </div>
        @endif
    </div>

    {{-- Reviews --}}
    <div class="mt-10 border-t pt-6 col-span-1 md:col-span-2">
        <livewire:tenant.frontend.shopping.reviews.product-reviews :product="$product" />
    </div>
</div>
