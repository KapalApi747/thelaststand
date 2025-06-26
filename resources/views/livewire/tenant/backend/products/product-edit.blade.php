<div class="p-6 space-y-6">

    <h3 class="h3 font-bold mb-4">Editing Product: <span class="text-gray-700">{{ $product->name }}</span></h3>

    @if (session()->has('message'))
        <div class="text-green-600 font-semibold">{{ session('message') }}</div>
    @endif

    <div class="p-6 border shadow rounded">
        <form wire:submit.prevent="updateProduct" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label>Name</label>
                <input type="text" wire:model.defer="name" class="w-full border p-2 rounded"/>
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label>SKU</label>
                <input type="text" wire:model.defer="sku" class="w-full border p-2 rounded"/>
                @error('sku') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label>Description</label>
                <textarea wire:model.defer="description" class="w-full border p-2 rounded"></textarea>
                @error('description') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4 flex flex-col">
                <label class="block font-semibold mb-2">Categories</label>

                <div class="flex flex-col">
                    @foreach ($allCategories as $category)
                        <label class="inline-flex items-center space-x-2">
                            <input
                                type="checkbox"
                                value="{{ $category->id }}"
                                wire:model="categoryIds"
                                class="form-checkbox"
                            >
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>

                @error('categoryIds') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>


            <div class="mb-4">
                <label>Price</label>
                <input type="number" wire:model.defer="price" class="w-full border p-2 rounded"/>
                @error('price') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label>Stock Quantity</label>
                <input type="number" wire:model.defer="stock" class="w-full border p-2 rounded"/>
                @error('stock') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label>Status</label>
                <select wire:model.defer="is_active" class="w-full border p-2 rounded">
                    <option value="0">Inactive</option>
                    <option value="1">Active</option>
                </select>
                @error('is_active') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-6">
                <label>Existing Images:</label>
                <div class="flex flex-wrap gap-4">
                    @foreach($existingImages as $image)
                        <div class="relative">
                            <img
                                src="{{ asset('tenant' . tenant()->id . '/' . $image['path']) }}"
                                class="w-24 h-24 object-cover rounded border-4 {{ $image['is_main_image'] ? 'border-green-500' : 'border-gray-300' }}"
                            >

                            <button wire:click="setMainImage({{ $image['id'] }})"
                                    class="absolute bottom-0 left-0 bg-blue-500 text-white px-1 text-xs rounded">
                                {{ $image['is_main_image'] ? 'Main' : 'Set as Main' }}
                            </button>

                            <button wire:click="deleteImage({{ $image['id'] }})"
                                    class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full text-xs">
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label for="newImages">Add Images:</label>
                <input type="file" id="newImages" wire:model="newImages" multiple>
                @error('newImages.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6">
                <button type="submit" class="btn">
                    Update Product
                </button>
            </div>

        </form>
    </div>

    <div class="p-6 border shadow rounded">
        <livewire:tenant.backend.products.product-variants :product="$product" />
    </div>

</div>
