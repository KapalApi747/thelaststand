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

        <label class="inline-flex items-center mt-2">
            <input type="checkbox" wire:model="is_active" class="form-checkbox">
            <span class="ml-2">Active</span>
        </label>

        <button wire:click="saveVariant" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
            {{ $variantEditId ? 'Update Variant' : 'Add Variant' }}
        </button>

        @if($variantEditId)
            <button wire:click="resetForm" class="ml-2 mt-4 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
        @endif
    </div>

    <table class="w-full border-collapse border">
        <thead>
        <tr class="bg-gray-200">
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
                <td class="border p-2">{{ $variant->name }}</td>
                <td class="border p-2">{{ $variant->sku }}</td>
                <td class="border p-2">{{ $variant->description }}</td>
                <td class="border p-2">€{{ number_format($variant->price, 2) }}</td>
                <td class="border p-2">{{ $variant->stock }}</td>
                <td class="border p-2">{{ $variant->is_active ? 'Yes' : 'No' }}</td>
                <td class="border p-2">
                    <button wire:click="editVariant({{ $variant->id }})" class="text-blue-600 mr-2">Edit</button>
                    <button wire:click="deleteVariant({{ $variant->id }})" class="text-red-600">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
