<div class="space-y-6">

    <h2 class="h2 font-bold mb-4">All Customers</h2>

    <div class="grid grid-cols-1 max-w-sm mb-6">
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Unique Customers</p>
            <p class="text-2xl font-semibold">{{ $customerCount }}</p>
        </div>
    </div>

    <div class="mb-6">
        @if (session()->has('message'))
            <div class="p-2 bg-green-200 text-green-800 rounded">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="bg-white shadow rounded p-4">

        <div class="flex flex-wrap gap-4 mb-6">

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search customers (use customer name, email) ..."
                class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3"
            />

            <select wire:model.live="is_active" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Statuses</option>
                <option value="true">Active</option>
                <option value="false">Inactive</option>
            </select>

            <select wire:model.live="pagination" class="border border-gray-300 rounded px-3 py-2">
                <option value="20">20 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>

            <select wire:model.live="sortField" class="border border-gray-300 rounded px-3 py-2">
                <option value="name">Customer Name</option>
                <option value="email">Email</option>
                <option value="created_at">Date Added</option>
                <option value="total_spent">Total Spent</option>
            </select>

            <input type="number" wire:model.live="minTotalSpent" step="0.01" placeholder="Min € spent" class="border px-2 py-1 rounded" />

            <select wire:model.live="sortDirection" class="border border-gray-300 rounded px-3 py-2">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>

        </div>

        @if (count($selectedCustomers) > 0)
            <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
                {{ count($selectedCustomers) }} customer{{ count($selectedCustomers) === 1 ? '' : 's' }} selected.
            </div>
        @endif

        <div class="my-5 flex items-center gap-12">

            <div class="flex flex-col">

                <div>
                    <input type="checkbox" id="selectAll" wire:model.live="selectAllCustomers">
                    <label for="selectAll" class="ml-1">All Customers On Database</label>
                </div>

                <div>
                    <input type="checkbox" id="selectAllOnPage" wire:model.live="selectAllOnPage" />
                    <label for="selectAllOnPage" class="ml-1">All Customers On This Page</label>
                </div>

                <div>
                    <input type="checkbox" id="selectAllFiltered" wire:model.live="selectAllFiltered" />
                    <label for="selectAllFiltered" class="ml-1">All Filtered Customers</label>
                </div>

            </div>
            <div class="flex items-center gap-2 ml-3">
                <select wire:model.live="bulkAction" class="form-select">
                    <option value="">-- Bulk Actions --</option>
                    <option value="update_status">Update Status</option>
                    <option value="export">Export Selected</option>
                </select>

                @if ($bulkAction === 'update_status')
                    <div class="flex items-center gap-2">
                        <select wire:model.live="newStatus" class="form-select">
                            <option value="">Choose new status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>

                        <button wire:click="updateBulkStatus" class="btn btn-success">
                            Update
                        </button>
                    </div>
                @endif
                @if($bulkAction === 'export')
                    <button wire:click="applyBulkAction" class="btn btn-primary">Export</button>
                @endif
            </div>

        </div>

        <table class="min-w-full text-left">
            <thead class="bg-gray-200">
            <tr>
                <th class="border p-2 text-center">Select</th>
                <th class="border p-2">Name</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Phone</th>
                <th class="border p-2">Total Spent</th>
                <th class="border p-2">Joined At</th>
                <th class="border p-2 text-center">Status</th>
                <th class="border p-2 text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @if (count($customers) > 0)
                @foreach ($customers as $customer)
                    <tr>
                        <td class="border p-2 text-center">
                            <input type="checkbox" wire:model.live="selectedCustomers" value="{{ $customer->id }}">
                        </td>
                        <td class="border p-2">{{ $customer->name }}</td>
                        <td class="border p-2">{{ $customer->email }}</td>
                        <td class="border p-2">{{ $customer->phone }}</td>
                        <td class="border p-2">
                            €{{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}
                        </td>
                        <td class="border p-2">{{ $customer->created_at->format('Y-m-d') }}</td>
                        <td class="border p-2 text-center">
                        <span class="px-2 py-1 font-semibold rounded-full">
                            @if ($customer->is_active == 1)
                                <i class="fa-solid fa-circle-check text-green-600"></i>
                            @else
                                <i class="fa-solid fa-circle-xmark text-red-600"></i>
                            @endif
                        </span>
                        </td>
                        <td class="border p-2">
                            <div class="flex justify-evenly items-center">
                                <a
                                    href="{{ route('tenant-dashboard.customer-view', $customer) }}"
                                    class="text-blue-600"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a
                                    href="{{ route('tenant-dashboard.customer-edit', $customer) }}"
                                    class="text-orange-600"
                                >
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="border p-2">No customers found matching your search criteria.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $customers->links() }}
    </div>
</div>
