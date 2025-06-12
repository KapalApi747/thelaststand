<div class="p-12 bg-black rounded shadow">
    <h2 class="text-3xl font-bold mb-12">Settings</h2>
    <div class="max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-12 text-center">Change Password</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="updatePassword">
            @csrf
            <div class="mb-4">
                <label for="current_password" class="block font-medium mb-1">Current Password</label>
                <input type="password" id="current_password" wire:model.defer="current_password" class="w-full border rounded px-3 py-2" autocomplete="current-password" />
                @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="new_password" class="block font-medium mb-1">New Password</label>
                <input type="password" id="new_password" wire:model.defer="new_password" class="w-full border rounded px-3 py-2" autocomplete="new-password" />
                @error('new_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-12">
                <label for="new_password_confirmation" class="block font-medium mb-1">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" wire:model.defer="new_password_confirmation" class="w-full border rounded px-3 py-2" autocomplete="new-password" />
            </div>

            <div class="flex justify-between">
                <a
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                    href=" {{ route('shop.shop-products') }}"
                >
                    Back To Shop
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
