<div class="space-y-6 p-6">

    <h3 class="h3 text-bold">Create New Shipping Methods</h3>

    @if (session()->has('message'))
        <div class="p-2 bg-green-200 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="p-6 bg-white rounded shadow">
        <form wire:submit.prevent="save" class="space-y-6">
            @csrf
            <div>
                <label for="code" class="block font-semibold mb-1">Code</label>
                <input id="code" type="text" wire:model.live="code" required
                       class="px-3 py-2 border-2 rounded" />
                @error('code') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="label" class="block font-semibold mb-1">Label</label>
                <input id="label" type="text" wire:model.live="label" required
                       class="px-3 py-2 border-2 rounded" />
                @error('label') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cost" class="block font-semibold mb-1">Cost (€)</label>
                <input id="cost" type="number" step="0.01" wire:model.live="cost" required
                       class="px-3 py-2 border-2 rounded" />
                @error('cost') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="carriers" class="block font-semibold mb-1">Carriers <span class="text-sm text-gray-600">( comma-separated )</span></label>
                <input id="carriers" type="text" wire:model.live="carriers"
                       class="px-3 py-2 border-2 rounded" />
                @error('carriers') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input id="enabled" type="checkbox" wire:model.defer="enabled"
                       class="px-3 py-2 border-2 rounded" />
                <label for="enabled" class="ml-2 block text-sm text-gray-700">Enabled</label>
            </div>

            <div>
                <button type="submit"
                        class="btn btn-primary">
                    Save Shipping Method
                </button>
            </div>
        </form>
    </div>
</div>
