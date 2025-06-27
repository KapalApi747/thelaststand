<div class="space-y-6">

    @if (session()->has('message'))
        <div class="p-2 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveCategory">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Category Name</label>
            <input type="text" wire:model.defer="name" class="w-full border p-2 rounded"/>
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Belongs to (optional):</label>
            <select wire:model="parent_id" class="w-full border p-2 rounded">
                <option value="">-- None --</option>
                @foreach ($allCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('parent_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mt-6">
            <button type="submit" class="btn">
                Add New Category
            </button>
        </div>

    </form>
</div>
