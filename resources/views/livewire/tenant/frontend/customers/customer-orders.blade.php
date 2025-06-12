<div class="bg-black rounded px-12 py-6">
    <h1 class="text-2xl font-bold text-center">My Orders</h1>
    <table class="mt-6 w-full">
        <thead>
        <tr class="border-b border-gray-400">
            <th class="py-5">#</th>
            <th class="py-5">Date</th>
            <th class="py-5">Items</th>
            <th class="py-5">Shipping Method</th>
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
                <td class="text-center py-5 uppercase">{{ $order->shipment?->shipping_method ?? 'No shipping' }}</td>
                <td class="text-center py-5">€{{ $order->total_amount }}</td>
                <td class="text-center py-5 uppercase">{{ $order->status }}</td>
                <td class="text-center py-5">
                    <a href="{{ route('shop.customer-order-view', $order) }}" class="text-blue-300 hover:underline">View Order</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
