<div class="p-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>
            <p class="text-black"><strong>SKU:</strong> {{ $product->sku }}</p>
            <p class="text-black"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p class="text-black"><strong>Stock:</strong> {{ $product->stock }}</p>
            <p class="text-black"><strong>Active:</strong> {{ $product->is_active ? 'Yes' : 'No' }}</p>
            <p class="text-black"><strong>Description:</strong> {{ $product->description }}</p>
        </div>
        <div>
            <a
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                href="{{ route('tenant-dashboard.product-edit', $product) }}">Edit</a>
        </div>
    </div>


    <div class="mt-4">
        <strong>Categories:</strong>
        <ul class="list-disc ml-5">
            @foreach ($product->categories as $category)
                <li>{{ $category->name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="mt-4">
        <strong>Images:</strong>
        <div class="grid grid-cols-3 gap-4 mt-2">
            @foreach ($product->images as $img)
                <img src="{{ asset('tenant' . tenant()->id . '/' . $img->path) }}" class="w-full rounded">
            @endforeach
        </div>
    </div>
</div>
