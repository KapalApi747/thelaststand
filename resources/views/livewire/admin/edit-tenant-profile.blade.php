<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold">Edit Tenant Profile: {{ $tenant->store_name }}</h1>

    @if (session()->has('message'))
        <div class="p-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveProfile">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block">Email</label>
                <input type="email" wire:model="profile.email" class="w-full border rounded p-2">
                @error('profile.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">Phone</label>
                <input type="text" wire:model="profile.phone" class="w-full border rounded p-2">
                @error('profile.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">Address</label>
                <input type="text" wire:model="profile.address" class="w-full border rounded p-2">
                @error('profile.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">City</label>
                <input type="text" wire:model="profile.city" class="w-full border rounded p-2">
                @error('profile.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">State</label>
                <input type="text" wire:model="profile.state" class="w-full border rounded p-2">
                @error('profile.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">ZIP</label>
                <input type="text" wire:model="profile.zip" class="w-full border rounded p-2">
                @error('profile.zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">Country</label>
                <input type="text" wire:model="profile.country" class="w-full border rounded p-2">
                @error('profile.country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">VAT ID</label>
                <input type="text" wire:model="profile.vat_id" class="w-full border rounded p-2">
                @error('profile.vat_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block">Business Description</label>
                <textarea wire:model="profile.business_description" rows="4" class="w-full border rounded p-2"></textarea>
                @error('profile.business_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block">Store Status</label>
                <select wire:model="profile.store_status" class="w-full border rounded p-2">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('profile.store_status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Changes
            </button>
            <a href="{{ route('dashboard.admin.tenant-profiles') }}" class="ml-4 text-gray-700 hover:underline">Cancel</a>
        </div>
    </form>
</div>
