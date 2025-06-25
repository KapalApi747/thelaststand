<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">All Orders</h3>

    <div class="grid grid-cols-3 md:grid-cols-1 gap-6 mb-6">
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Total Orders</p>
            <p class="text-2xl font-semibold">{{ $orderCount }}</p>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Total Revenue</p>
            <p class="text-2xl font-semibold">${{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded p-4">

        <div class="mb-6">
            @if (session()->has('message'))
                <div class="p-2 bg-green-200 text-green-800 rounded">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <div class="flex flex-wrap gap-4 mb-6">

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search orders (use order number, customer name) ..."
                class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3"
            />

            <select wire:model.live="status" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Statuses</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
                <option value="refunded">Refunded</option>
                <option value="failed">Failed</option>
            </select>

            <select wire:model.live="pagination" class="border border-gray-300 rounded px-3 py-2">
                <option value="20">20 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>

            <select wire:model.live="sortField" class="border border-gray-300 rounded px-3 py-2">
                <option value="order_number">Order Number</option>
                <option value="customer.name">Customer Name</option>
                <option value="total_amount">Amount</option>
                <option value="created_at">Date Added</option>
            </select>

            <input type="number" wire:model.live="minAmount" step="0.01" placeholder="Minimum Amount (€)" class="border px-2 py-1 rounded" />

            <select wire:model.live="sortDirection" class="border border-gray-300 rounded px-3 py-2">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>

        </div>

        @if (count($selectedOrders) > 0)
            <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
                {{ count($selectedOrders) }} order{{ count($selectedOrders) === 1 ? '' : 's' }} selected.
            </div>
        @endif

        <div class="my-5 flex items-center">
            <div class="flex flex-col">
                <div>
                    <input type="checkbox" wire:model.live="selectAllOrders">
                    <label for="selectAll" class="ml-1">Select All Orders</label>
                </div>

                <div>
                    <input type="checkbox" id="selectAllOnPage" wire:model.live="selectAllOnPage" />
                    <label for="selectAllOnPage" class="ml-1">All Orders On This Page</label>
                </div>

                <div>
                    <input type="checkbox" id="selectAllFiltered" wire:model.live="selectAllFiltered" />
                    <label for="selectAllFiltered" class="ml-1">All Filtered Orders</label>
                </div>
            </div>
            <div class="flex items-center gap-4 ml-12">
                <select wire:model.live="bulkAction" class="form-select">
                    <option value="">-- Bulk Actions --</option>
                    <option value="update_status">Update Status</option>
                    <option value="export">Export Selected</option>
                    <option value="print_invoices">Print Invoices</option>
                </select>

                @if ($bulkAction === 'update_status')
                    <div class="flex items-center gap-2">
                        <select wire:model.live="newStatus" class="form-select">
                            <option value="">-- Choose new status --</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="refunded">Refunded</option>
                            <option value="failed">Failed</option>
                        </select>

                        <div>
                            <button wire:click="updateBulkStatus" class="btn-info">
                                Update
                            </button>
                        </div>

                    </div>
                @elseif ($bulkAction === 'export')
                    <div>
                        <button wire:click="applyBulkAction" class="btn-info">
                            Export
                        </button>
                    </div>
                @elseif ($bulkAction === 'print_invoices')
                    <div>
                        <button wire:click="applyBulkAction" class="btn-info">
                            Print Invoices
                        </button>
                    </div>
                @endif
            </div>

        </div>

        <table class="min-w-full text-left">
            <thead class="bg-gray-200">
            <tr>
                <th class="border p-2 text-center">Select</th>
                <th class="border p-2">#</th>
                <th class="border p-2">Customer</th>
                <th class="border p-2">Amount</th>
                <th class="border p-2">Date</th>
                <th class="border p-2 text-center">Status</th>
                <th class="border p-2 text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="border p-2 text-center">
                        <input type="checkbox" wire:model.live="selectedOrders" value="{{ $order->id }}">
                    </td>
                    <td class="border p-2">{{ $order->order_number }}</td>
                    <td class="border p-2">{{ $order->customer->name ?? 'N/A' }}</td>
                    <td class="border p-2">€{{ number_format($order->total_amount, 2) }}</td>
                    <td class="border p-2">{{ $order->created_at->format('Y-m-d') }}</td>

                    <td class="border p-2 text-center">
                        <span class="px-2 py-1 font-semibold rounded-full {{ $this->allStatuses($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>

                    <td class="border p-2">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('tenant-dashboard.order-view', $order) }}"
                               class="text-blue-600 hover:text-blue-800 transition-colors duration-300 mr-3"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('tenant-dashboard.order-edit', $order) }}"
                               class="text-yellow-500 hover:text-yellow-700 transition-colors duration-300"
                            >
                                <i class="far fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
</div>
