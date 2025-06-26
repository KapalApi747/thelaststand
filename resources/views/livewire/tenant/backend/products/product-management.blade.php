<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">All Products</h3>



    <div class="bg-white p-4 rounded shadow mt-6">

        <div class="mb-6">
            @if (session()->has('message'))
                <div class="p-2 bg-green-200 text-green-800 rounded">
                    {{ session('message') }}
                </div>
            @endif
        </div>

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
                <option value="10">10 per page</option>
                <option value="20">20 per page</option>
                <option value="50">50 per page</option>
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
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-lg">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                    <tr>
                        <th class="p-3">Image</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Slug</th>
                        <th class="p-3">SKU</th>
                        <th class="p-3">Categories</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Stock</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                    @foreach ($products as $product)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="p-2">
                                @php
                                    $image = $product->images->first();
                                    $imageUrl = $image ? asset('tenant' . tenant()->id . '/' . $image->path) : 'https://placehold.co/80x80?text=No+Image';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Product Image" class="w-16 h-16 object-cover rounded">
                            </td>
                            <td class="p-2 font-medium">{{ $product->name }}</td>
                            <td class="p-2">{{ $product->slug }}</td>
                            <td class="p-2">{{ $product->sku }}</td>
                            <td class="p-2">{{ implode(', ', $product->categories->pluck('name')->toArray()) }}</td>
                            <td class="p-2">€{{ number_format($product->price, 2) }}</td>
                            <td class="p-2">{{ $product->stock }}</td>
                            <td class="p-2">{{ ucfirst($product->is_active ? 'Active' : 'Inactive') }}</td>
                            <td class="p-2 text-center">
                                <div class="flex justify-center items-center">
                                    <div class="mr-3">
                                        <a href="{{ route('tenant-dashboard.product-view', $product) }}"
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-300"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="mr-3">
                                        <a href="{{ route('tenant-dashboard.product-edit', $product) }}"
                                           class="text-yellow-500 hover:text-yellow-700 transition-colors duration-300"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <button
                                            onclick="confirm('Are you sure you want to delete this product?') || event.stopImmediatePropagation()"
                                            wire:click="deleteProduct({{ $product->id }})"
                                            class="text-red-600 hover:text-red-800 transition-colors duration-300"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
