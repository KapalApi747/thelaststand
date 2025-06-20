<div class="p-6">
    <h3 class="h3 font-bold mb-4">
        Tenant: <span class="font-semibold text-gray-600">{{ $tenant->store_name }}</span>
    </h3>

    @if (session()->has('message'))
        <div class="p-4 mb-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-1 gap-6">
        {{-- Left Column: Tenant Profile --}}
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block font-semibold">Email:</label>
                    <p class="text-gray-600">{{ $tenant->profile->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">Phone</label>
                    <p class="text-gray-600">{{ $tenant->profile->phone ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">Address</label>
                    <p class="text-gray-600">{{ $tenant->profile->address ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">City</label>
                    <p class="text-gray-600">{{ $tenant->profile->city ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">State</label>
                    <p class="text-gray-600">{{ $tenant->profile->state ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">ZIP</label>
                    <p class="text-gray-600">{{ $tenant->profile->zip ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">Country</label>
                    <p class="text-gray-600">{{ $tenant->profile->country ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">VAT ID</label>
                    <p class="text-gray-600">{{ $tenant->profile->vat_id ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">Business Description</label>
                    <p class="text-gray-600">{{ $tenant->profile->business_description ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block font-semibold">Store Status</label>
                    <p class="text-gray-600 {{ $tenant->profile->store_status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $tenant->profile->store_status }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Column: Tenant Statistics --}}
        <div>
            <div class="p-4 border rounded shadow bg-gray-200">
                <h2 class="text-xl font-semibold mb-4">Tenant Shop Statistics</h2>

                <div class="mb-4">
                    <label for="periodStart" class="block font-medium">Start Date</label>
                    <input wire:model="periodStart" type="date" id="periodStart" class="border rounded px-2 py-1 w-full" />
                </div>

                <div class="mb-4">
                    <label for="periodEnd" class="block font-medium">End Date</label>
                    <input wire:model="periodEnd" type="date" id="periodEnd" class="border rounded px-2 py-1 w-full" />
                </div>

                <button wire:click="loadStats"
                        wire:loading.attr="disabled"
                        class="btn btn-primary">
                    Refresh Stats
                </button>

                <div class="mt-6">
                    @if ($loading)
                        <p>Loading income...</p>
                    @elseif ($errorMessage)
                        <p class="text-red-600">{{ $errorMessage }}</p>
                    @else
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 p-4 rounded-xl shadow">
                                <h3 class="text-sm font-semibold text-gray-600">Total Revenue</h3>
                                <p class="text-xl font-bold text-green-600">€{{ number_format($totalRevenue, 2) }}</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl shadow">
                                <h3 class="text-sm font-semibold text-gray-600">Total Completed Orders</h3>
                                <p class="text-xl font-bold text-gray-800">{{ $orderCount }}</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl shadow">
                                <h3 class="text-sm font-semibold text-gray-600">Avg Completed Order Value</h3>
                                <p class="text-xl font-bold text-gray-800">€{{ number_format($averageOrderValue, 2) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
