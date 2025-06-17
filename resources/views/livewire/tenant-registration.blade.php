<div class="bg-gray-950 py-16 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-5xl mx-auto bg-gray-900 p-10 rounded-xl shadow-lg border border-gray-800">
        <h1 class="text-3xl font-bold text-white text-center mb-10 tracking-wide">
            Tenant Registration
        </h1>

        @if ($success)
            <div class="bg-green-900 text-white p-6 rounded-lg mb-6 text-center">
                <h2 class="text-xl font-semibold mb-2">Store Created!</h2>
                <p class="mb-4">Your store has been created successfully!</p>
                <a href="{{ $storeUrl }}"
                   target="_blank"
                   class="inline-block bg-white text-green-700 font-semibold px-6 py-2 rounded hover:bg-gray-100 transition">
                    Visit My Shop
                </a>
            </div>
        @endif

        @if (!$success)
        <form wire:submit.prevent="tenantRegistrationFunction" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Store Info -->
                <div>
                    <h2 class="text-xl font-semibold text-white mb-6">Store Information</h2>

                    <div class="space-y-6">
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-300 mb-1">Store Name *</label>
                            <input type="text" id="store_name" wire:model.live="store_name"
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('store_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="domain" class="block text-sm font-medium text-gray-300 mb-1">
                                Domain * <span class="text-gray-500 text-xs">(without .thelaststand.local)</span>
                            </label>
                            <input type="text" id="domain" wire:model.live="domain"
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('domain') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email *</label>
                            <input type="email" id="email" wire:model.live="email"
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password *</label>
                            <input type="password" id="password" wire:model.live="password"
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-300 mb-1">Store Logo</label>
                            <input type="file" id="logo" accept="image/*" wire:model.live="logo"
                                   class="block w-full text-sm text-gray-400 bg-gray-800 border border-gray-700 rounded-md file:bg-indigo-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded file:cursor-pointer">
                            @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div>
                    <h2 class="text-xl font-semibold text-white mb-6">Profile Info</h2>

                    <div class="space-y-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Phone</label>
                            <input type="text" id="phone" wire:model.live="phone"
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-300 mb-1">Address</label>
                            <input type="text" id="address" wire:model.live="address"
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-300 mb-1">City</label>
                                <input type="text" id="city" wire:model.live="city"
                                       class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-300 mb-1">State</label>
                                <input type="text" id="state" wire:model.live="state"
                                       class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="zip" class="block text-sm font-medium text-gray-300 mb-1">ZIP</label>
                                <input type="text" id="zip" wire:model.live="zip"
                                       class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-300 mb-1">Country</label>
                                <input type="text" id="country" wire:model.live="country"
                                       class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="business_description" class="block text-sm font-medium text-gray-300 mb-1">
                                Business Description
                            </label>
                            <textarea id="business_description" wire:model.live="business_description"
                                      rows="4"
                                      class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            @error('business_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6 text-center">
                <p class="block text-sm font-medium text-gray-300">Fields marked with <span class="text-red-500">*</span> are required!</p>
            </div>

            <!-- Submit Button -->
            <div class="pt-10 text-center">
                <button type="submit"
                        class="inline-flex items-center justify-center w-full md:w-auto bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-md font-semibold transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="tenantRegistrationFunction"
                >
                    <svg wire:loading wire:target="tenantRegistrationFunction" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>

                    <span wire:loading.remove wire:target="tenantRegistrationFunction">Register Store</span>
                    <span wire:loading.inline wire:target="tenantRegistrationFunction">Processing...</span>
                </button>
            </div>

        </form>
        @endif
    </div>
</div>
