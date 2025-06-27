<div class="space-y-6 p-6">

    <div class="flex justify-between">
        <h3 class="h3 font-bold mb-4">{{ $product->name }}</h3>
        <div>
            <a
                class="btn"
                href="{{ route('tenant-dashboard.product-edit', $product) }}">Edit</a>
        </div>
    </div>

    <div class="p-6 bg-white rounded shadow">
        <div>
            <p class="text-black"><strong>SKU:</strong> {{ $product->sku }}</p>
            <p class="text-black"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p class="text-black"><strong>Stock:</strong> {{ $product->stock }}</p>
            <p class="text-black"><strong>Active:</strong> {{ $product->is_active ? 'Yes' : 'No' }}</p>
            <p class="text-black"><strong>Description:</strong> {{ $product->description }}</p>
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
                    <div class="w-full h-64 overflow-hidden">
                        <img src="{{ asset('tenant' . tenant()->id . '/' . $img->path) }}"
                             class="w-full h-full object-cover rounded">
                    </div>

                @endforeach
            </div>
        </div>

        @if ($product->variants->count())
            <div class="mt-8">
                <h4 class="font-semibold text-gray-800 text-lg mb-4">Variants</h4>
                <div class="space-y-6">
                    @foreach ($product->variants as $variant)
                        <div class="border rounded p-4 bg-gray-50 shadow-sm">
                            <div class="flex justify-between items-center mb-2">
                                <h5 class="text-md font-medium">{{ $variant->name }}</h5>
                                <span class="text-sm text-gray-600">{{ $variant->sku }}</span>
                            </div>

                            <p class="text-gray-700"><strong>Price:</strong> ${{ number_format($variant->price, 2) }}</p>
                            <p class="text-gray-700"><strong>Stock:</strong> {{ $variant->stock }}</p>
                            <p class="text-gray-700"><strong>Active:</strong> {{ $variant->is_active ? 'Yes' : 'No' }}</p>
                            <p class="text-gray-700"><strong>Description:</strong> {{ $variant->description }}</p>

                            @if ($variant->images->count())
                                <div class="grid grid-cols-3 gap-4 mt-4">
                                    @foreach ($variant->images as $img)
                                        <div class="w-full h-64 overflow-hidden rounded">
                                            <img src="{{ asset('tenant' . tenant()->id . '/' . $img->path) }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
