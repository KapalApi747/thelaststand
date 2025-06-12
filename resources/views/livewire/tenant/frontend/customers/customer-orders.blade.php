<div class="bg-black rounded p-12">
    <div class="flex justify-between">
        <h2 class="text-3xl font-bold">My Orders</h2>
        <a
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
            href=" {{ route('shop.shop-products') }}"
        >
            Back To Shop
        </a>
    </div>

    <table class="mt-6 w-full">
        <thead>
        <tr class="border-b border-gray-400">
            <th class="py-5">#</th>
            <th class="py-5">Date</th>
            <th class="py-5">Items</th>
            <th class="py-5">Shipping Methods</th>
            <th class="py-5">Amount</th>
            <th class="py-5">Status</th>
            <th class="py-5">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td class="text-center py-5">{{ $order->order_number }}</td>
                <td class="text-center py-5">{{ $order->created_at->format('Y-m-d') }}</td>
                <td class="text-center py-5">{{ $order->items->count() }}</td>
                <td class="text-center py-5 uppercase">
                    {{ $order->shipments->pluck('shipping_method')->unique()->implode(', ') }}
                </td>
                <td class="text-center py-5">€{{ $order->total_amount }}</td>
                <td class="text-center py-5 uppercase">{{ $order->status }}</td>
                <td class="text-center py-5">
                    <a href="{{ route('shop.customer-order-view', $order) }}"
                       class="text-blue-300 hover:text-blue-500 me-3"
                    >
                        View Order
                    </a>
                    <button wire:click="exportPDF({{ $order->id }})"
                            class="text-orange-400 px-4 py-2 rounded hover:text-orange-500 cursor-pointer"
                    >
                        Download
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
