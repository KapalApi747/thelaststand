<div class="space-y-8">

    <h3 class="h3 font-semibold">Edit Order #{{ $order->order_number }}</h3>

    {{-- Order Details --}}
    <div class="bg-white p-6 shadow rounded-xl space-y-4">
        <h2 class="font-bold text-lg">Order Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model="status" class="form-select w-full">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="paid">Paid</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="refunded">Refunded</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tax Amount (€)</label>
                <input type="number" wire:model.live="taxAmount" step="0.01" class="form-input w-full" />
                @error('taxAmount') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Shipping Cost (€)</label>
                <input type="number" wire:model.live="shippingCost" step="0.01" class="form-input w-full" />
                @error('shippingCost') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    {{-- Customer Info --}}
    <div class="bg-white p-6 shadow rounded-xl space-y-4">
        <h2 class="font-bold text-lg">Customer Info</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" wire:model.live="customerName" class="form-input w-full" />
                @error('customerName') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model.live="customerEmail" class="form-input w-full" />
                @error('customerEmail') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" wire:model.live="customerPhone" class="form-input w-full" />
                @error('customerPhone') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    {{-- Billing & Shipping Addresses --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Billing Address --}}
        <div class="bg-white p-6 shadow rounded-xl space-y-4">
            <h2 class="font-bold text-lg">Billing Address</h2>

            @foreach(['full_name', 'address_line1', 'address_line2', 'city', 'state', 'zip', 'country', 'phone'] as $field)
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                    <input type="text" wire:model.live="billingAddress.{{ $field }}" class="form-input w-full" />
                    @error('billingAddress.' . $field) <span class="text-red-600">{{ $message }}</span> @enderror
                </div>

            @endforeach
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white p-6 shadow rounded-xl space-y-4">
            <h2 class="font-bold text-lg">Shipping Address</h2>

            @foreach(['full_name', 'address_line1', 'address_line2', 'city', 'state', 'zip', 'country', 'phone'] as $field)
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                    <input type="text" wire:model.live="shippingAddress.{{ $field }}" class="form-input w-full" />
                    @error('shippingAddress.' . $field) <span class="text-red-600">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-6">
        @if (session()->has('message'))
            <div class="alert alert-success alert-close">
                <button class="alert-btn-close">
                    <i class="fad fa-times"></i>
                </button>
                <span>{{ session('message') }}</span>
            </div>
        @endif
    </div>

    {{-- Action Buttons --}}
    <div class="flex justify-end gap-4 pt-4">
        <a href="{{ route('tenant-dashboard.order-index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            Return
        </a>
        <button wire:click="saveOrder" class="btn btn-primary">
            Save Changes
        </button>
    </div>
</div>
