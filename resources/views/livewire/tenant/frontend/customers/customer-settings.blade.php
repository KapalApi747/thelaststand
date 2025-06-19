<div class="max-w-6xl mx-auto mt-6 p-6 bg-white rounded-md shadow">
    <h2 class="text-3xl font-bold mb-12 text-gray-900">Settings</h2>
    <div class="max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-12 text-center text-gray-900">Change Password</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="updatePassword">
            @csrf
            <div class="mb-4">
                <label for="current_password" class="block font-medium mb-1 text-gray-800">Current Password</label>
                <input type="password" id="current_password" wire:model.defer="current_password"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white"
                       autocomplete="current-password" />
                @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="new_password" class="block font-medium mb-1 text-gray-800">New Password</label>
                <input type="password" id="new_password" wire:model.defer="new_password"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white"
                       autocomplete="new-password" />
                @error('new_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-12">
                <label for="new_password_confirmation" class="block font-medium mb-1 text-gray-800">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" wire:model.defer="new_password_confirmation"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-gray-900 bg-white"
                       autocomplete="new-password" />
            </div>

            <div class="flex justify-between">
                <a
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors duration-300"
                    href="{{ route('shop.shop-products') }}"
                >
                    Back To Shop
                </a>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer transition-colors duration-300">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
