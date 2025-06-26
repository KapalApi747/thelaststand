<div class="max-w-2xl mx-auto mt-6 p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Checkout Information</h2>

    <form wire:submit.prevent="submit" novalidate>
        @csrf

        @if (!$loggedInCustomer)
            <div class="mb-5">
                <label for="name" class="block font-medium text-gray-700 mb-1">Full Name</label>
                <input wire:model.lazy="name" type="text" id="name" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label for="email" class="block font-medium text-gray-700 mb-1">Email Address</label>
                <input wire:model.lazy="email" type="email" id="email" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            @if($showLoginButton)
                <div class="my-6 text-center">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline text-sm font-medium">
                        Already have an account? Log in
                    </a>
                </div>
            @endif

            <div class="mb-5">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="askForAccount" class="mr-2 text-indigo-600 focus:ring-indigo-500 rounded" />
                    <span class="text-gray-700">Create an account for faster checkout next time</span>
                </label>
            </div>

            @if ($askForAccount)
                <div class="mb-5">
                    <label for="password" class="block font-medium text-gray-700 mb-1">Password</label>
                    <input wire:model.lazy="password" type="password" id="password" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="block font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input wire:model.lazy="password_confirmation" type="password" id="password_confirmation" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                </div>
            @endif
        @else
            <p class="mb-4 text-gray-700"><strong>Name:</strong> {{ $name }}</p>
            <p class="mb-6 text-gray-700"><strong>Email:</strong> {{ $email }}</p>
        @endif

        <h2 class="text-2xl font-bold text-gray-800 mb-6">Phone & Address</h2>

        <div class="mb-5">
            <label for="phone" class="block font-medium text-gray-700 mb-1">Phone</label>
            <input wire:model.live="phone" type="text" id="phone" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-5">
            <label for="address_line1" class="block font-medium text-gray-700 mb-1">Address Line 1</label>
            <input wire:model.live="address_line1" type="text" id="address_line1" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
            @error('address_line1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-5">
            <label for="address_line2" class="block font-medium text-gray-700 mb-1">Address Line 2 (optional)</label>
            <input wire:model.live="address_line2" type="text" id="address_line2" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
            @error('address_line2') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <label for="zip" class="block font-medium text-gray-700 mb-1">ZIP / Postal Code</label>
                <input wire:model.live="zip" type="text" id="zip" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="city" class="block font-medium text-gray-700 mb-1">City</label>
                <input wire:model.live="city" type="text" id="city" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <label for="state" class="block font-medium text-gray-700 mb-1">State / Province</label>
                <input wire:model.live="state" type="text" id="state" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="country" class="block font-medium text-gray-700 mb-1">Country</label>
                <input wire:model.live="country" type="text" id="country" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-5">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model.live="billingDifferent" class="mr-2 text-indigo-600 focus:ring-indigo-500 rounded">
                <span class="text-gray-700">My billing address is different from my shipping address</span>
            </label>
        </div>

        @if ($billingDifferent)
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Billing Address</h3>

            <div class="mb-5">
                <label for="billing_address_line1" class="block font-medium text-gray-700 mb-1">Address Line 1</label>
                <input wire:model.live="billing_address_line1" type="text" id="billing_address_line1" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('billing_address_line1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label for="billing_address_line2" class="block font-medium text-gray-700 mb-1">Address Line 2 (optional)</label>
                <input wire:model.live="billing_address_line2" type="text" id="billing_address_line2" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                @error('billing_address_line2') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label for="billing_city" class="block font-medium text-gray-700 mb-1">City</label>
                    <input wire:model.live="billing_city" type="text" id="billing_city" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                    @error('billing_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="billing_state" class="block font-medium text-gray-700 mb-1">State / Province</label>
                    <input wire:model.live="billing_state" type="text" id="billing_state" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                    @error('billing_state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="billing_zip" class="block font-medium text-gray-700 mb-1">ZIP / Postal Code</label>
                    <input wire:model.live="billing_zip" type="text" id="billing_zip" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                    @error('billing_zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="billing_country" class="block font-medium text-gray-700 mb-1">Country</label>
                    <input wire:model.live="billing_country" type="text" id="billing_country" class="w-full border text-gray-700 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
                    @error('billing_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif

        <button
            type="submit"
            class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 transition-colors duration-300 cursor-pointer"
            wire:loading.attr="disabled"
            wire:target="submitButton"
        >
            <span wire:loading.remove wire:target="submitButton">
                Continue to Shipping
            </span>
            <span wire:loading wire:target="submitButton" class="flex items-center justify-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Loading...</span>
            </span>
        </button>
    </form>
</div>
