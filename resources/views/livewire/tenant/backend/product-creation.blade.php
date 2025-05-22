<div class="space-y-4">


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
            <select wire:model.defer="status" class="w-full border p-2 rounded">
                <option value="draft">Draft</option>
                <option value="active">Active</option>
            </select>
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
