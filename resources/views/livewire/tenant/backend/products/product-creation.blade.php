<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">Create New Product</h3>

    @if (session()->has('message'))
        <div class="alert alert-success alert-close">
            <button class="alert-btn-close">
                <i class="fad fa-times"></i>
            </button>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="saveProduct" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" wire:model.defer="name" class="w-full border p-2 rounded"/>
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>SKU</label>
            <input type="text" wire:model.defer="sku" class="w-full border p-2 rounded"/>
            @error('sku') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Description</label>
            <textarea wire:model.defer="description" class="w-full border p-2 rounded"></textarea>
            @error('description') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="categoryIds">Categories</label>
            <select wire:model="categoryIds" multiple class="form-multiselect w-full">
                @foreach ($allCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('categoryIds') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>


        <div>
            <label>Price</label>
            <input type="number" wire:model.defer="price" class="w-full border p-2 rounded"/>
            @error('price') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Stock Quantity</label>
            <input type="number" wire:model.defer="stock" class="w-full border p-2 rounded"/>
            @error('stock') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Status</label>
            <select wire:model.defer="is_active" class="w-full border p-2 rounded">
                <option value="0">Inactive</option>
                <option value="1">Active</option>
            </select>
            @error('is_active') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Images</label>
            <input type="file" wire:model="images" multiple class="w-full p-2"/>
            @error('images.*') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Save Product
        </button>
    </form>
</div>
