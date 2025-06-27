<div class="space-y-6 p-6">

    @if (session()->has('message'))
        <div class="alert alert-success alert-close">
            <button class="alert-btn-close">
                <i class="fad fa-times"></i>
            </button>
            <span>{{ session('message') }}</span>
        </div>
    @endif

        <h4 class="h4 font-bold mt-6">Store Profile Info</h4>

        <div class="p-6 bg-white shadow rounded">
            <form wire:submit.prevent="submitProfileChanges" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" wire:model.lazy="profile.email" class="w-full rounded border p-2" />
                    @error('profile.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" wire:model.lazy="profile.phone" class="w-full rounded border p-2" />
                    @error('profile.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="address">Address</label>
                    <input id="address" type="text" wire:model.lazy="profile.address" class="w-full rounded border p-2" />
                    @error('profile.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city">City</label>
                        <input id="city" type="text" wire:model.lazy="profile.city" class="w-full rounded border p-2" />
                        @error('profile.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="state">State</label>
                        <input id="state" type="text" wire:model.lazy="profile.state" class="w-full rounded border p-2" />
                        @error('profile.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="zip">ZIP</label>
                        <input id="zip" type="text" wire:model.lazy="profile.zip" class="w-full rounded border p-2" />
                        @error('profile.zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="country">Country</label>
                        <input id="country" type="text" wire:model.lazy="profile.country" class="w-full rounded border p-2" />
                        @error('profile.country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="vat_id">VAT ID</label>
                    <input id="vat_id" type="text" wire:model.lazy="profile.vat_id" class="w-full rounded border p-2" />
                    @error('profile.vat_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="business_description">Business Description</label>
                    <textarea id="business_description" wire:model.lazy="profile.business_description"
                              class="w-full rounded border p-2"
                              rows="4"
                    >
                </textarea>
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

                <div>
                    <button type="submit" class="btn">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>


        <h4 class="h4 font-bold mt-6">Store Settings</h4>

        <div class="max-w-2xl bg-white p-6 shadow rounded">
            <form wire:submit.prevent="submitStoreChanges" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="storeName">Store Name:</label>
                    <input type="text" wire:model.defer="storeName" id="storeName" class="border p-2 w-full"/>

                    @error('storeName')
                    <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-6">
                    <label>Current Logo:</label>
                    @if ($currentLogoUrl)
                        <img src="{{ $currentLogoUrl }}" class="w-24 h-24 border mt-2 object-contain"
                             alt="Current Store Logo"/>
                    @else
                        <p class="text-gray-500">No logo uploaded.</p>
                    @endif
                </div>

                <div class="mt-6">
                    <label for="logo">Upload New Logo:</label>
                    <input type="file" wire:model="logo" id="logo" class="mt-2"/>

                    <div wire:loading wire:target="logo" class="mt-2">
                        <svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <p class="text-center text-gray-600 mt-1">Uploading logo...</p>
                    </div>

                    @error('logo')
                    <div class="text-red-500">{{ $message }}</div>
                    @enderror

                    @if ($logo)
                        <p class="text-sm mt-2 text-gray-600">Preview:</p>
                        <img src="{{ $logo->temporaryUrl() }}" class="w-24 h-24 border mt-2 object-contain"
                             alt="Logo Preview"/>
                    @endif
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn-info">
                        Save Store Changes
                    </button>
                </div>

            </form>
        </div>
</div>

