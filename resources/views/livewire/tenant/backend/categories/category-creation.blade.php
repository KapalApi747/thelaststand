<div class="space-y-4">

    @if (session()->has('message'))
        <div class="text-green-600">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="saveCategory">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Category Name</label>
            <input type="text" wire:model.defer="name" class="w-full border p-2 rounded"/>
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Parent Category (optional)</label>
            <select wire:model="parent_id" class="w-full border p-2 rounded">
                <option value="">-- None --</option>
                @foreach ($allCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('parent_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Save Category
        </button>
    </form>
</div>
