<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-6">Create New Customer</h3>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600 font-medium">{{ session('message') }}</div>
    @endif

    <div class="bg-white p-6 shadow rounded-md">
        <form wire:submit.prevent="save" class="space-y-6">
            @csrf

            <h2 class="text-lg font-semibold my-6">Customer Info</h2>

            <!-- Basic Info -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Name</label>
                    <input type="text" wire:model.live="name" class="w-full border-2 px-3 py-2 rounded"/>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" wire:model.live="email" class="w-full border-2 px-3 py-2 rounded"/>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-1">Phone</label>
                    <input type="text" wire:model.live="phone" class="w-full border-2 px-3 py-2 rounded"/>
                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-1">Status</label>
                    <select wire:model.live="is_active" class="w-full border-2 px-3 py-2 rounded">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-4">
                <div>
                    <label class="block font-semibold mb-1">Password</label>
                    <input type="password" wire:model.defer="password" class="w-full border-2 px-3 py-2 rounded"/>
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-1">Confirm Password</label>
                    <input type="password" wire:model.defer="password_confirmation" class="w-full border-2 px-3 py-2 rounded"/>
                </div>
            </div>

            <!-- Shipping Address -->
            <div>
                <h2 class="text-lg font-semibold my-6">Shipping Address</h2>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" placeholder="Address Line 1" wire:model.live="shipping.address_line1"
                           class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="Address Line 2" wire:model.live="shipping.address_line2"
                           class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="City" wire:model.live="shipping.city" class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="State" wire:model.live="shipping.state" class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="Zip" wire:model.live="shipping.zip" class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="Country" wire:model.live="shipping.country" class="w-full border-2 px-3 py-2 rounded"/>
                </div>
            </div>

            <!-- Billing Address -->
            <div>
                <h2 class="text-lg font-semibold my-6">Billing Address (optional)</h2>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" placeholder="Address Line 1" wire:model.live="billing.address_line1"
                           class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="Address Line 2" wire:model.live="billing.address_line2"
                           class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="City" wire:model.live="billing.city" class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="State" wire:model.live="billing.state" class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="Zip" wire:model.live="billing.zip" class="w-full border-2 px-3 py-2 rounded"/>
                    <input type="text" placeholder="Country" wire:model.live="billing.country" class="w-full border-2 px-3 py-2 rounded"/>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-6 px-4 py-2 rounded">
                Save Customer
            </button>
        </form>
    </div>
</div>
