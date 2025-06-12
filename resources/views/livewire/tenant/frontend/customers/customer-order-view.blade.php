<div class="bg-black text-white p-12 space-y-8">

    {{-- Order Summary --}}
    <div>
        <div class="flex justify-between mb-6">
            <a
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                href=" {{ route('shop.customer-orders') }}">
                Back
            </a>
            <button wire:click="exportPDF"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Download PDF
            </button>
        </div>

        <h2 class="text-2xl font-bold mb-6">Order Summary</h2>

        <table class="w-full table-auto text-left border-collapse">
            <tbody>
            <tr><th class="py-2 w-1/4">Order Number:</th><td>{{ $order->order_number }}</td></tr>
            <tr><th class="py-2">Total Amount:</th><td>€{{ number_format($order->total_amount, 2) }}</td></tr>
            <tr><th class="py-2">Tax:</th><td>€{{ number_format($order->tax_amount, 2) }}</td></tr>
            <tr><th class="py-2">Shipping Cost:</th><td>€{{ number_format($order->shipping_cost, 2) }}</td></tr>
            <tr><th class="py-2">Status:</th><td>{{ ucfirst($order->status) }}</td></tr>
            <tr><th class="py-2">Placed On:</th><td>{{ $order->created_at->format('Y-m-d H:i') }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Addresses --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Shipping & Billing Addresses</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @foreach ($order->addresses as $address)
                <div class="border border-gray-600 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">{{ ucfirst($address->type) }} Address</h3>
                    <p>{{ $address->full_name }}</p>
                    <p>{{ $address->address_line1 }}</p>
                    @if ($address->address_line2)
                        <p>{{ $address->address_line2 }}</p>
                    @endif
                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                    <p>{{ $address->country }}</p>
                    <p class="mt-2">📞 {{ $address->phone }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Order Items --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Items</h2>
        <table class="w-full border border-gray-600">
            <thead>
            <tr class="bg-gray-800 text-left">
                <th class="p-3">Product</th>
                <th class="p-3">Price</th>
                <th class="p-3">Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->items as $item)
                <tr class="border-t border-gray-700">
                    <td class="p-3">{{ $item->product_name }}</td>
                    <td class="p-3">€{{ number_format($item->price, 2) }}</td>
                    <td class="p-3">{{ $item->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Payment Info --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Payment Information</h2>
        <table class="w-full table-auto text-left border-collapse">
            <thead>
            <tr class="bg-gray-800">
                <th class="p-3">Method</th>
                <th class="p-3">Amount</th>
                <th class="p-3">Status</th>
                <th class="p-3">Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->payments as $payment)
                <tr class="border-t border-gray-700">
                    <td class="p-3">{{ ucfirst($payment->payment_method) }}</td>
                    <td class="p-3">€{{ number_format($payment->amount, 2) }}</td>
                    <td class="p-3">{{ ucfirst($payment->status) }}</td>
                    <td class="p-3">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Shipment Info --}}
    @if ($order->shipments->count())
        <div>
            <h2 class="text-xl font-semibold mb-4">Shipments ({{ $order->shipments->count() }})</h2>

            @foreach ($order->shipments as $index => $shipment)
                <div class="mb-6 border border-gray-600 rounded p-4 bg-gray-900">
                    <h3 class="font-bold text-lg mb-2">Shipment #{{ $index + 1 }}</h3>
                    <table class="w-full table-auto text-left border-collapse">
                        <tbody>
                        <tr>
                            <th class="py-2 w-1/4">Tracking Number:</th>
                            <td>{{ $shipment->tracking_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2">Carrier:</th>
                            <td>{{ $shipment->carrier ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2">Status:</th>
                            <td>{{ ucfirst($shipment->status) ?? 'Pending' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2">Shipping Method:</th>
                            <td class="uppercase">{{ $shipment->shipping_method ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2">Shipping Cost:</th>
                            <td>€{{ number_format($shipment->shipping_cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="py-2">Shipped At:</th>
                            <td>{{ optional($shipment->shipped_at)->format('Y-m-d H:i') ?? 'Not yet shipped' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2">Delivered At:</th>
                            <td>{{ optional($shipment->delivered_at)->format('Y-m-d H:i') ?? 'Not yet delivered' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

</div>
