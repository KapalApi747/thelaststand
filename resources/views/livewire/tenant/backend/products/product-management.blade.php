<div class="space-y-6">

    @if (session()->has('message'))
        <div class="p-2 bg-green-200 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    {{--<livewire:tenant.backend.products.product-creation/>--}}

    <div class="bg-white p-4 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">My Products</h2>

        <div class="flex flex-wrap gap-4 mb-6">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search products (use product name, sku, description)..."
                class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3"
            />

            <select wire:model.live="category" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="status" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <select wire:model.live="pagination" class="border border-gray-300 rounded px-3 py-2">
                <option value="12">12 per page</option>
                <option value="24">24 per page</option>
                <option value="48">48 per page</option>
            </select>

            <select wire:model.live="sortField" class="border border-gray-300 rounded px-3 py-2">
                <option value="name">Name</option>
                <option value="price">Price</option>
                <option value="created_at">Date Added</option>
            </select>

            <select wire:model.live="sortDirection" class="border border-gray-300 rounded px-3 py-2">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>

        </div>

        @if ($products->isEmpty())
            <p class="text-gray-600">The product list is currently empty. Please adjust your search criteria.</p>
        @else
            <div class="grid grid-cols-4 xl:grid-cols-2 gap-4">
                @foreach ($products as $product)
                    <div class="border p-3 rounded shadow-sm flex flex-col items-center">
                        <div class="flex flex-col">
                            <h3 class="font-bold text-lg text-center my-6">{{ $product->name }}</h3>
                            <div class="flex justify-center mb-6">
                                @php
                                    $image = $product->images->first();
                                    $imageUrl = $image ? asset('tenant' . tenant()->id . '/' . $image->path) : 'https://placehold.co/200x200?text=No+Image';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Main Product Image"
                                     class="w-40 h-40 object-cover rounded">
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">Slug: {{ $product->slug }}</p>
                                <p class="text-sm text-gray-700">SKU: {{ $product->sku }}</p>
                                <p class="text-sm text-gray-700">
                                    Categories: {{ implode(', ', $product->categories->pluck('name')->toArray()) }}</p>
                                <p class="text-sm text-gray-700">Price: €{{ number_format($product->price, 2) }}</p>
                                <p class="text-sm text-gray-700">Stock: {{ $product->stock }}</p>
                                <p class="text-sm text-gray-700">
                                    Status: {{ ucfirst($product->is_active ? 'active' : 'inactive') }}</p>
                            </div>
                        </div>
                        <div class="flex items-end my-6">
                            <div>
                                <a href="{{ route('tenant-dashboard.product-view', $product) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mt-2">View</a>
                            </div>
                            <div>
                                <a href="{{ route('tenant-dashboard.product-edit', $product) }}"
                                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mt-2 ml-2">Edit</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
