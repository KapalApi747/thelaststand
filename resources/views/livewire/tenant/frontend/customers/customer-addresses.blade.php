<div class="max-w-6xl mx-auto mt-6 p-6 bg-white rounded shadow">
    <h2 class="text-3xl font-bold mb-6 text-gray-900">My Addresses</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if ($isEditing)
        <form wire:submit.prevent="saveAddress" class="space-y-4">
            <div class="max-w-xs">
                <label class="block font-semibold mb-1 text-gray-800">Type</label>
                <select wire:model.defer="editingAddress.type" class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-gray-900">
                    <option value="shipping">Shipping</option>
                    <option value="billing">Billing</option>
                </select>
                @error('editingAddress.type') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1 text-gray-800">Address Line 1</label>
                <input type="text" wire:model.defer="editingAddress.address_line1" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white" />
                @error('editingAddress.address_line1') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-800">Address Line 2</label>
                <input type="text" wire:model.defer="editingAddress.address_line2" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white" />
                @error('editingAddress.address_line2') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-800">City</label>
                <input type="text" wire:model.defer="editingAddress.city" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white" />
                @error('editingAddress.city') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-800">State</label>
                <input type="text" wire:model.defer="editingAddress.state" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white" />
                @error('editingAddress.state') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-800">Zip</label>
                <input type="text" wire:model.defer="editingAddress.zip" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white" />
                @error('editingAddress.zip') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-800">Country</label>
                <input type="text" wire:model.defer="editingAddress.country" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white" />
                @error('editingAddress.country') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-between mt-12">
                <button type="button" wire:click="cancelEdit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 cursor-pointer transition-colors duration-300">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer transition-colors duration-300">Save</button>
            </div>
        </form>
    @else
        <button wire:click="addAddress" class="mb-6 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors duration-300 cursor-pointer">
            Add New Address
        </button>

        @if (count($addresses) > 0)
            <ul class="space-y-4">
                @foreach ($addresses as $address)
                    <li class="border border-gray-300 p-4 rounded shadow-sm flex justify-between items-center bg-white">
                        <div class="text-gray-900">
                            <strong>{{ ucfirst($address['type'] ?? 'Address') }}</strong><br>
                            {{ $address['address_line1'] }} {{ $address['address_line2'] }}<br>
                            {{ $address['city'] }}, {{ $address['state'] }} {{ $address['zip'] }}<br>
                            {{ $address['country'] }}
                        </div>
                        <div class="space-x-2">
                            <button wire:click="editAddress({{ $address['id'] }})" class="text-blue-600 hover:text-blue-800 cursor-pointer transition-colors duration-300">Edit</button>
                            <button wire:click="deleteAddress({{ $address['id'] }})" class="text-red-600 hover:text-red-800 cursor-pointer transition-colors duration-300" onclick="confirm('Delete this address?') || event.stopImmediatePropagation()">Delete</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-700">You have no saved addresses.</p>
        @endif
    @endif
</div>
