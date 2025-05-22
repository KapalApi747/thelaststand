<div>

    @if (session()->has('message'))
        <div class="p-2 bg-green-200 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <livewire:tenant.backend.product-creation />

    <div class="bg-white p-4 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">My Products</h2>

        @if ($products->isEmpty())
            <p class="text-gray-600">You haven't added any products yet.</p>
        @else
            <div class="grid grid-cols-4 lg:grid-cols-2 gap-4">
                @foreach ($products as $product)
                    <div class="border p-3 rounded shadow-sm">
                        <h3 class="font-bold text-lg">{{ $product->name }}</h3>
                        <img src="{{ asset('tenant' . tenant()->id . '/' . $product->images->first()->path) }}"
                             alt="Main Product Image" class="w-40 h-40 object-cover rounded mb-2">
                        <p class="text-sm text-gray-700">SKU: {{ $product->sku }}</p>
                        <p class="text-sm text-gray-700">Price: €{{ number_format($product->price, 2) }}</p>
                        <p class="text-sm text-gray-700">Stock: {{ $product->stock }}</p>
                        <p class="text-sm text-gray-700">Status: {{ ucfirst($product->status) }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
