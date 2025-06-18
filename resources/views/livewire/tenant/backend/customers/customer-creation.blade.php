<div class="space-y-6">
    <div class="max-w-3xl mx-auto p-8 bg-gray-300 rounded-md">
        <h1 class="text-2xl font-semibold mb-6">Create New Customer</h1>

        @if (session()->has('message'))
            <div class="mb-4 text-green-600 font-medium">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="save" class="space-y-6">
            @csrf
            <!-- Basic Info -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Name</label>
                    <input type="text" wire:model.live="name" class="w-full" />
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" wire:model.live="email" class="w-full" />
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Phone</label>
                    <input type="text" wire:model.live="phone" class="w-full" />
                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Status</label>
                    <select wire:model.live="is_active" class="w-full">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Password</label>
                    <input type="password" wire:model.live="password" class="w-full" />
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Confirm Password</label>
                    <input type="password" wire:model.live="password_confirmation" class="w-full" />
                </div>
            </div>

            <!-- Shipping Address -->
            <div>
                <h2 class="text-lg font-semibold mt-6">Shipping Address</h2>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" placeholder="Address Line 1" wire:model.live="shipping.address_line1" class="w-full" />
                    <input type="text" placeholder="Address Line 2" wire:model.live="shipping.address_line2" class="w-full" />
                    <input type="text" placeholder="City" wire:model.live="shipping.city" class="w-full" />
                    <input type="text" placeholder="State" wire:model.live="shipping.state" class="w-full" />
                    <input type="text" placeholder="Zip" wire:model.live="shipping.zip" class="w-full" />
                    <input type="text" placeholder="Country" wire:model.live="shipping.country" class="w-full" />
                </div>
            </div>

            <!-- Billing Address -->
            <div>
                <h2 class="text-lg font-semibold mt-6">Billing Address (optional)</h2>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" placeholder="Address Line 1" wire:model.live="billing.address_line1" class="w-full" />
                    <input type="text" placeholder="Address Line 2" wire:model.live="billing.address_line2" class="w-full" />
                    <input type="text" placeholder="City" wire:model.live="billing.city" class="w-full" />
                    <input type="text" placeholder="State" wire:model.live="billing.state" class="w-full" />
                    <input type="text" placeholder="Zip" wire:model.live="billing.zip" class="w-full" />
                    <input type="text" placeholder="Country" wire:model.live="billing.country" class="w-full" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-6 px-4 py-2 rounded">
                Save Customer
            </button>
        </form>
    </div>

</div>
