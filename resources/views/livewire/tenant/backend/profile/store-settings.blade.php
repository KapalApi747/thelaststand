<div class="space-y-6">

    @if (session()->has('message'))
        <div class="text-green-600">
            {{ session('message') }}
        </div>
    @endif

        <h3 class="h3 font-bold mt-6">Store Profile Info</h3>

        <form wire:submit.prevent="submitProfileChanges" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" wire:model.lazy="profile.email" class="input" />
                @error('profile.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone">Phone</label>
                <input id="phone" type="text" wire:model.lazy="profile.phone" class="input" />
                @error('profile.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="address">Address</label>
                <input id="address" type="text" wire:model.lazy="profile.address" class="input" />
                @error('profile.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="city">City</label>
                    <input id="city" type="text" wire:model.lazy="profile.city" class="input" />
                    @error('profile.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="state">State</label>
                    <input id="state" type="text" wire:model.lazy="profile.state" class="input" />
                    @error('profile.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="zip">ZIP</label>
                    <input id="zip" type="text" wire:model.lazy="profile.zip" class="input" />
                    @error('profile.zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="country">Country</label>
                    <input id="country" type="text" wire:model.lazy="profile.country" class="input" />
                    @error('profile.country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="vat_id">VAT ID</label>
                <input id="vat_id" type="text" wire:model.lazy="profile.vat_id" class="input" />
                @error('profile.vat_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="business_description">Business Description</label>
                <textarea id="business_description" wire:model.lazy="profile.business_description" class="input" rows="4"></textarea>
                @error('profile.business_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="store_status">Store Status</label>
                <select id="store_status" wire:model="profile.store_status" class="input">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('profile.store_status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Profile Changes
            </button>
        </form>

        <h2 class="text-lg font-bold mt-6">Store Settings</h2>
        <form wire:submit.prevent="submitStoreChanges" enctype="multipart/form-data">
            <div>
                <label for="storeName">Store Name:</label>
                <input type="text" wire:model.defer="storeName" id="storeName" class="border p-2 w-full"/>

                @error('storeName')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label>Current Logo:</label>
                @if ($currentLogoUrl)
                    <img src="{{ $currentLogoUrl }}" class="w-24 h-24 border mt-2 object-contain"
                         alt="Current Store Logo"/>
                @else
                    <p class="text-gray-500">No logo uploaded.</p>
                @endif
            </div>

            <div>
                <label for="logo">Upload New Logo (max 200x200 pixels):</label>
                <input type="file" wire:model="logo" id="logo" class="mt-2"/>

                @error('logo')
                <div class="text-red-500">{{ $message }}</div>
                @enderror

                @if ($logo)
                    <p class="text-sm mt-2 text-gray-600">Preview:</p>
                    <img src="{{ $logo->temporaryUrl() }}" class="w-24 h-24 border mt-2 object-contain"
                         alt="Logo Preview"/>
                @endif
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Store Changes
            </button>
        </form>
</div>

