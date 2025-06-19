<div class="p-6 bg-white rounded shadow">

    <div class="flex justify-between align-center">
        <h3 class="h3 font-bold mb-4">Payout <span class="text-gray-600">#{{ $payout->id }}</span></h3>
        <div class="flex items-center gap-2">
            <button wire:click="exportCsv"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 transition-colors duration-300">
                <i class="fa-solid fa-file-csv mr-2"></i>CSV
            </button>
            <button wire:click="exportPdf"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition-colors duration-300">
                <i class="fa-solid fa-file-pdf mr-2"></i>PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-3 md:grid-cols-1 gap-6 mb-6">
        <div class="p-4 shadow rounded">
            <p class="text-gray-700">Total Payout</p>
            <p class="text-2xl font-semibold text-gray-700">€{{ number_format($payout->amount, 2) }}</p>
        </div>
        <div class="p-4 shadow rounded">
            <p class="text-gray-700">Orders Total</p>
            <p class="text-2xl font-semibold text-gray-700">€{{ number_format($orders->sum('total_amount'), 2) }}</p>
        </div>
        <div class="p-4 shadow rounded">
            <p class="text-gray-700">Commission Taken</p>
            <p class="text-2xl font-semibold text-gray-700">€{{ number_format($orders->sum('total_amount') - $payout->amount, 2) }}</p>
        </div>
    </div>

    <table class="min-w-full text-left">
        <thead class="bg-gray-200">
            <tr>
                <th class="border p-2">Order #</th>
                <th class="border p-2">Order Total</th>
                <th class="border p-2">Payout (-5% commission)</th>
                <th class="border p-2">Customer Info</th>
                <th class="border p-2">Order Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($this->orders as $order)
            <tr>
                <td class="border p-2">
                    <a
                        href="{{ route('tenant-dashboard.order-view', $order) }}"
                        class="text-teal-600 hover:text-teal-800 transition-colors duration-300 hover:underline"
                    >
                        {{ $order->id }}
                    </a>
                </td>
                <td class="border p-2">€{{ number_format($order->total_amount, 2) }}</td>
                <td class="border p-2">€{{ number_format($this->payoutCalculation($order), 2) }}</td>
                <td class="border p-2">
                    <a
                        href="{{ route('tenant-dashboard.customer-view', $order->customer) }}"
                        class="text-teal-600 hover:text-teal-800 transition-colors duration-300 hover:underline"
                    >
                        {{ $order->customer->name }}
                    </a>
                    <p class="text-black">{{ $order->customer->email }}</p>
                </td>
                <td class="border p-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
