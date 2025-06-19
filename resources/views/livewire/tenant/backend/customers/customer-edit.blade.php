<div class="space-y-6">
    <h2 class="h2 font-bold">Edit Customer: <span class="text-gray-700">{{ $customer->name }}</span></h2>

    @if (session()->has('message'))
        <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid grid-cols-2 md:grid-cols-1 gap-4">
            <div>
                <label>Name</label>
                <input type="text" wire:model.live="name" class="w-full rounded border p-2" />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Email</label>
                <input type="email" wire:model.live="email" class="w-full rounded border p-2" />
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Phone</label>
                <input type="text" wire:model.live="phone" class="w-full rounded border p-2" />
                @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Status</label>
                <select wire:model.defer="is_active" class="w-full rounded border p-2">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h2 class="text-lg font-semibold mt-6">Shipping Address</h2>
                @foreach (['address_line1', 'address_line2', 'city', 'state', 'zip', 'country'] as $field)
                    <input type="text" placeholder="{{ ucfirst(str_replace('_', ' ', $field)) }}"
                           wire:model.live="shipping.{{ $field }}"
                           class="w-full rounded border p-2 mt-2" />
                @endforeach
            </div>

            <div>
                <h2 class="text-lg font-semibold mt-6">Billing Address</h2>
                @foreach (['address_line1', 'address_line2', 'city', 'state', 'zip', 'country'] as $field)
                    <input type="text" placeholder="{{ ucfirst(str_replace('_', ' ', $field)) }}"
                           wire:model.live="billing.{{ $field }}"
                           class="w-full rounded border p-2 mt-2" />
                @endforeach
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('tenant-dashboard.customer-index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Back To Customer List
            </a>
            <button type="submit" class="btn btn-primary">
                Save Changes
            </button>
        </div>
    </form>
</div>
