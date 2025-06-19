<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">Edit Shipping Method: <span class="text-gray-700">{{ $shippingMethod->label }}</span></h3>

    @if (session()->has('message'))
        <div class="text-green-600">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="update" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium">Code</label>
            <input type="text" wire:model.live="code" class="w-full border rounded px-2 py-1">
            @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Label</label>
            <input type="text" wire:model.live="label" class="w-full border rounded px-2 py-1">
            @error('label') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Cost (€)</label>
            <input type="number" step="0.01" wire:model.live="cost" class="w-full border rounded px-2 py-1">
            @error('cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Carriers (comma-separated)</label>
            <input type="text" wire:model.live="carriers" class="w-full border rounded px-2 py-1">
            @error('carriers') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model.live="enabled" class="mr-2">
                Enabled
            </label>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
