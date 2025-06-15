<div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-black p-12">

    {{-- Image gallery --}}
    <div>
        @php
            $selected = $this->selectedVariant();
            $variantImage = $selected?->images->first()?->path ?? null;
            $mainImage = $product->mainImage?->path;
        @endphp

        <img src="{{ asset('tenant' . tenant()->id . '/' . ($variantImage ?? $mainImage)) }}"
             alt="{{ $product->name }}"
             class="w-full h-96 object-cover rounded-xl">

        <div class="flex mt-4 gap-2">
            @foreach ($product->images as $image)
                <img src="{{ asset('tenant' . tenant()->id . '/' . $image->path) }}"
                     class="w-20 h-20 object-cover rounded border">
            @endforeach
        </div>
    </div>

    {{-- Product Info --}}
    <div>
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>

            {{-- Back Button --}}
            <div>
                <a class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-300"
                   href="{{ route('shop.shop-products') }}">
                    Back
                </a>
            </div>
        </div>


        {{-- Price --}}
        <p class="text-xl text-gray-200 mt-2">
            €{{ number_format($selected?->price ?? $product->price, 2) }}
        </p>

        {{-- Stock --}}
        @php $stock = $selected?->stock ?? $product->stock; @endphp
        <p class="{{ $stock > 0 ? 'text-green-500' : 'text-red-500' }} mt-1">
            {{ $stock > 0 ? 'In Stock' : 'Out of Stock' }} ({{ $stock }})
        </p>

        {{-- Description --}}
        <p class="mt-4 text-gray-200">{{ $product->description }}</p>

        <p class="mt-4 text-gray-200">
            <strong>Categories:</strong> {{ $product->categories->implode('name', ', ') }}
        </p>

        {{-- Variant Selector --}}
        @if ($product->variants->isNotEmpty())
            <div class="mt-6">
                <h3 class="font-semibold mb-2 text-white">Choose a Variant (optional):</h3>
                <select wire:model.live="variantId" class="w-full rounded border-gray-300 bg-black text-white">
                    <option value="">-- No Variant --</option>
                    @foreach ($product->variants as $variant)
                        <option value="{{ $variant->id }}">
                            {{ $variant->name }} - €{{ number_format($variant->price, 2) }}
                            ({{ $variant->stock }} in stock)
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Add to Cart --}}
        <div class="flex items-center mt-6">
            @php
                $selectedVariant = $this->selectedVariant();
                $stock = $selectedVariant ? $selectedVariant->stock : $product->stock;
            @endphp

            <div class="me-3">
                <form method="POST" action="{{ route('shop.add-to-cart') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if ($variantId)
                        <input type="hidden" name="variant_id" value="{{ $variantId }}">
                    @endif

                    <button
                        type="submit"
                        class="btn px-4 py-2 rounded-md bg-green-500 hover:bg-green-700 transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $stock <= 0 ? 'disabled' : '' }}
                    >
                        Add to Cart
                    </button>
                </form>
            </div>

            <div>
                @if ($stock <= 0)
                    <p class="text-red-500 font-semibold">Out of Stock</p>
                @endif
            </div>
        </div>

        @if (session('message'))
            <div class="my-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 shadow-md" role="alert">
                {{ session('message') }}
            </div>
        @endif
    </div>

    {{-- Reviews --}}
    <div class="mt-10 border-t pt-6 col-span-2">
        <livewire:tenant.frontend.shopping.reviews.product-reviews :product="$product" />
    </div>
</div>
