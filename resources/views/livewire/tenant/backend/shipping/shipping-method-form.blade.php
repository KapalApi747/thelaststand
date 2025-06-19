<div class="space-y-8">
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
                <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                <input id="code" type="text" wire:model.live="code" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('code') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="label" class="block text-sm font-medium text-gray-700">Label</label>
                <input id="label" type="text" wire:model.live="label" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('label') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cost" class="block text-sm font-medium text-gray-700">Cost (€)</label>
                <input id="cost" type="number" step="0.01" wire:model.live="cost" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('cost') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="carriers" class="block text-sm font-medium text-gray-700">Carriers (comma-separated)</label>
                <input id="carriers" type="text" wire:model.live="carriers"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('carriers') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input id="enabled" type="checkbox" wire:model.defer="enabled"
                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
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
