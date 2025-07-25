<div>

    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <h3 class="text-xl font-semibold mb-4">Product Variants for: {{ $product->name }}</h3>

    <div class="mb-6">
        <input wire:model="name" type="text" placeholder="Variant Name" class="border p-2 rounded w-full mb-2">
        <input wire:model="sku" type="text" placeholder="SKU" class="border p-2 rounded w-full mb-2">
        <input wire:model="description" type="text" placeholder="Description" class="border p-2 rounded w-full mb-2">
        <input wire:model="price" type="number" step="0.01" placeholder="Price" class="border p-2 rounded w-full mb-2">
        <input wire:model="stock" type="number" placeholder="Stock" class="border p-2 rounded w-full mb-2">

        <div class="mb-4">
            <label>Status</label>
            <select wire:model.defer="is_active" class="w-full border p-2 rounded">
                <option value="0">Inactive</option>
                <option value="1">Active</option>
            </select>
            @error('is_active') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <input type="file" wire:model="images" multiple class="border p-2 rounded w-full mb-2">
        @error('images.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <div class="flex items-center mt-6">
            <div class="mr-4">
                <button wire:click="saveVariant" class="btn">
                    {{ $variantEditId ? 'Update Variant' : 'Add Variant' }}
                </button>
            </div>
            @if($variantEditId)
            <div>
                <button wire:click="resetForm" class="btn-danger">Cancel</button>
            </div>
            @endif
        </div>
    </div>

    <table class="w-full border-collapse border">
        <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">Picture</th>
            <th class="border p-2">Name</th>
            <th class="border p-2">SKU</th>
            <th class="border p-2">Description</th>
            <th class="border p-2">Price</th>
            <th class="border p-2">Stock</th>
            <th class="border p-2">Active</th>
            <th class="border p-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($variants as $variant)
            <tr>
                <td class="border p-2">
                    @if($variant->images)
                        @foreach($variant->images as $image)
                            <div class="flex justify-center items-center">
                                <img src="{{ asset('tenant' . tenant()->id . '/' . $image->path) }}" alt="{{ $variant->name }}" class="w-12 h-12 object-cover rounded">
                            </div>
                        @endforeach
                    @endif
                </td>
                <td class="border p-2">{{ $variant->name }}</td>
                <td class="border p-2">{{ $variant->sku }}</td>
                <td class="border p-2">{{ $variant->description }}</td>
                <td class="border p-2">€{{ number_format($variant->price, 2) }}</td>
                <td class="border p-2">{{ $variant->stock }}</td>
                <td class="border p-2">{{ $variant->is_active ? 'Yes' : 'No' }}</td>
                <td class="border p-2">
                    <div class="flex justify-center items-center">
                        <div class="mr-2">
                            <button wire:click="editVariant({{ $variant->id }})"
                                    class="text-blue-600"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div>
                            <button wire:click="deleteVariant({{ $variant->id }})"
                                    class="text-red-600"
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
