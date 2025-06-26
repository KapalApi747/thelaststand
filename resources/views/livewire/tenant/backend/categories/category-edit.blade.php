<div class="max-w-2xl space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">Editing Category: <span class="text-gray-700">{{ $category->name }}</span></h3>

    @if (session()->has('message'))
        <div class="p-2 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="updateCategory" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block font-medium">Category Name</label>
            <input type="text" id="name" wire:model.defer="name" class="w-full border p-2 rounded" />
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="parent_id" class="block font-medium">Parent Category (optional)</label>
            <select wire:model="parent_id" id="parent_id" class="w-full border p-2 rounded">
                <option value="">-- None --</option>
                @foreach ($allCategories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
            @error('parent_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mt-6">
            <button type="submit" class="btn">
                Update Category
            </button>
        </div>
    </form>
</div>
