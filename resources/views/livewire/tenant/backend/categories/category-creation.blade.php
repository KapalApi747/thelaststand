<div class="space-y-4">

    <form wire:submit.prevent="saveCategory">
        @csrf
        <div>
            <label>Category Name</label>
            <input type="text" wire:model.defer="name" class="w-full border p-2 rounded"/>
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Save Category
        </button>
    </form>
</div>
