<div class="max-w-6xl mx-auto mt-6 bg-white shadow rounded p-6">
    <h2 class="text-3xl font-bold text-gray-900">My Orders</h2>
    <div class="my-6">
        <a
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors duration-300"
            href="{{ route('shop.shop-products') }}"
        >
            Back To Shop
        </a>
    </div>

    <table class="w-full text-left border border-gray-200 rounded overflow-hidden">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm tracking-wider text-center">
        <tr>
            <th class="py-4 px-3 border-b">#</th>
            <th class="py-4 px-3 border-b">Date</th>
            <th class="py-4 px-3 border-b">Items</th>
            <th class="py-4 px-3 border-b">Shipping Methods</th>
            <th class="py-4 px-3 border-b">Amount</th>
            <th class="py-4 px-3 border-b">Status</th>
            <th class="py-4 px-3 border-b">Actions</th>
        </tr>
        </thead>
        <tbody class="text-gray-800">
        @foreach($orders as $order)
            <tr class="hover:bg-gray-50 transition">
                <td class="py-4 px-3 text-center">{{ $order->order_number }}</td>
                <td class="py-4 px-3 text-center">{{ $order->created_at->format('Y-m-d') }}</td>
                <td class="py-4 px-3 text-center">{{ $order->items->count() }}</td>
                <td class="py-4 px-3 text-center uppercase">
                    {{ $order->shipments->pluck('shipping_method')->unique()->implode(', ') }}
                </td>
                <td class="py-4 px-3 text-center">€{{ $order->total_amount }}</td>
                <td class="py-4 px-3 text-center uppercase">{{ $order->status }}</td>
                <td class="py-4 px-3 text-center whitespace-nowrap">
                    <a href="{{ route('shop.customer-order-view', $order) }}"
                       class="text-blue-600 hover:text-blue-800 font-medium me-3 transition"
                    >
                        View Order
                    </a>
                    <button wire:click="exportPDF({{ $order->id }})"
                            class="text-orange-600 hover:text-orange-800 font-medium transition"
                    >
                        Download
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
