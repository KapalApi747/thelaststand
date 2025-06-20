<div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 sm:p-8 md:p-12 bg-white rounded-lg shadow-lg text-gray-800">

    {{-- Image gallery --}}
    <div
        x-data="{ mainImage: '' }"
        x-init="
            mainImage = '{{ asset('tenant' . tenant()->id . '/' . ($product->mainImage?->path ?? 'https://placehold.co/400x400?text=No+Image')) }}'
        "
        x-on:variant-image-updated.window="mainImage = $event.detail[0].url"
        class="flex flex-col"
    >
        {{-- Main Image --}}
        <img
            :src="mainImage"
            alt="{{ $product->name }}"
            class="w-full h-72 sm:h-96 md:h-[28rem] object-contain rounded-lg border border-gray-300 shadow-sm transition duration-300 ease-in-out"
        >

        {{-- Main Product Images Thumbnails --}}
        <div class="mt-6">
            <h3 class="text-gray-900 font-semibold mb-3">Main Product Images</h3>
            <div class="flex flex-wrap gap-3">
                @foreach ($product->images->where('product_variant_id', null) as $image)
                    <img
                        src="{{ asset('tenant' . tenant()->id . '/' . $image->path) }}"
                        @click="mainImage = '{{ asset('tenant' . tenant()->id . '/' . $image->path) }}'"
                        class="w-20 h-20 object-cover rounded-lg border border-gray-300 cursor-pointer hover:ring-2 hover:ring-indigo-500 transition-all"
                    >
                @endforeach
            </div>
        </div>

        {{-- Variant Images Thumbnails --}}
        @if ($product->variants->count())
            @foreach ($product->variants as $variant)
                <div class="mt-8">
                    <h3 class="text-gray-900 font-semibold mb-3">Image(s) for {{ $product->name }} - {{ $variant->name }}</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($variant->images as $variantImage)
                            <img
                                src="{{ asset('tenant' . tenant()->id . '/' . $variantImage->path) }}"
                                @click="mainImage = '{{ asset('tenant' . tenant()->id . '/' . $variantImage->path) }}'"
                                class="w-20 h-20 object-cover rounded-lg border border-gray-300 cursor-pointer hover:ring-2 hover:ring-indigo-500 transition-all"
                            >
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Product Info --}}
    <div class="flex flex-col justify-between bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $product->name }}</h1>

                {{-- Back Button --}}
                <a
                    href="{{ route('shop.shop-products') }}"
                    class="inline-block bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-700 transition-colors duration-300 text-center font-semibold shadow-sm"
                >
                    Back
                </a>
            </div>

            {{-- Price --}}
            <p class="text-2xl text-indigo-600 font-semibold mt-4">
                €{{ number_format($selected?->price ?? $product->price, 2) }}
            </p>

            {{-- Stock --}}
            <p class="{{ $this->stock > 0 ? 'text-green-600' : 'text-red-600' }} mt-2 font-medium">
                {{ $this->stock > 0 ? 'In Stock' : 'Out of Stock' }} ({{ $this->stock }})
            </p>

            {{-- Description --}}
            <p class="mt-5 text-gray-700 leading-relaxed text-base sm:text-lg">{{ $product->description }}</p>

            {{-- Categories --}}
            <p class="mt-6 text-gray-600 text-sm sm:text-base">
                <strong class="font-semibold text-gray-800">Categories:</strong> {{ $product->categories->implode('name', ', ') }}
            </p>

            {{-- Variant Selector --}}
            @if ($product->variants->isNotEmpty())
                <div class="mt-8">
                    <h3 class="font-semibold mb-2 text-gray-900 text-base sm:text-lg">Choose a Variant (optional):</h3>
                    <select
                        wire:model.live="variantId"
                        class="w-full rounded-md border border-gray-300 bg-white text-gray-900 text-base sm:text-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    >
                        <option value="{{ null }}">-- Standard -- ({{ $product->stock }} in stock)</option>
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
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mt-8">
            <form method="POST" action="{{ route('shop.add-to-cart') }}" class="flex w-full sm:w-auto">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @if ($variantId)
                    <input type="hidden" name="variant_id" value="{{ $variantId }}">
                @endif

                <button
                    type="submit"
                    class="btn w-full sm:w-auto px-6 py-3 rounded-md bg-indigo-600 hover:bg-indigo-700 transition-colors duration-300 font-semibold text-white shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ $this->stock <= 0 ? 'disabled' : '' }}
                >
                    Add to Cart
                </button>
            </form>

            @if ($this->stock <= 0)
                <p class="text-red-600 font-semibold">Out of Stock</p>
            @endif
        </div>

        @if (session('message'))
            <div class="mt-6 rounded-lg bg-green-50 px-5 py-4 text-green-800 shadow-inner" role="alert">
                {{ session('message') }}
            </div>
        @endif
    </div>

    {{-- Reviews --}}
    <div class="mt-10 border-t border-gray-300 pt-8 col-span-1 md:col-span-2">
        <livewire:tenant.frontend.shopping.reviews.product-reviews :product="$product" />
    </div>
</div>
