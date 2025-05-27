<div>
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-6">Available Products</h1>
        </div>
        <div>
            <a
                class="bg-teal-600 text-white py-2 px-4 rounded hover:bg-teal-700 transition"
                href="{{ route('shop.shop-cart') }}">Shopping Cart</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse ($products as $product)
            <div class="bg-white rounded-xl shadow p-4">
                @php
                    $image = $product->images->first();
                    $imageUrl = $image ? asset('tenant' . tenant()->id . '/' . $image->path) : 'https://placehold.co/200x200?text=No+Image';
                @endphp
                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     class="w-full h-48 object-cover rounded">

                <h2 class="text-lg font-semibold mt-4">{{ $product->name }}</h2>
                <p class="text-teal-600 font-bold">€{{ number_format($product->price, 2) }}</p>

                <livewire:tenant.frontend.shopping.add-to-cart-button :product="$product" />
            </div>
        @empty
            <p>No products available.</p>
        @endforelse
    </div>
</div>
