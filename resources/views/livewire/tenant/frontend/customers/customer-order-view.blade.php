<div class="max-w-6xl mx-auto mt-6 bg-white text-gray-900 p-6 space-y-8 shadow-md rounded">

    {{-- Order Summary --}}
    <div>
        <h2 class="text-3xl font-bold mb-6 text-gray-900">Order Summary</h2>
        <div class="flex justify-between mb-6">
            <a
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors duration-300"
                href="{{ route('customer-orders') }}">
                Back
            </a>
            <button wire:click="exportPDF"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-300 cursor-pointer"
            >
                Download PDF
            </button>
        </div>

        <table class="w-full table-auto text-left border-collapse border border-gray-300 rounded">
            <tbody>
            <tr>
                <th class="py-2 w-1/4 border-b border-gray-300 font-semibold">Order Number:</th>
                <td class="border-b border-gray-300">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <th class="py-2 border-b border-gray-300 font-semibold">Total Amount:</th>
                <td class="border-b border-gray-300">€{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <th class="py-2 border-b border-gray-300 font-semibold">Tax:</th>
                <td class="border-b border-gray-300">€{{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <th class="py-2 border-b border-gray-300 font-semibold">Shipping Cost:</th>
                <td class="border-b border-gray-300">€{{ number_format($order->shipping_cost, 2) }}</td>
            </tr>
            <tr>
                <th class="py-2 border-b border-gray-300 font-semibold">Status:</th>
                <td class="border-b border-gray-300">{{ ucfirst($order->status) }}</td>
            </tr>
            <tr>
                <th class="py-2 font-semibold">Placed On:</th>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    {{-- Addresses --}}
    <div>
        <h2 class="text-xl font-semibold mb-4 text-gray-900">Shipping & Billing Addresses</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @foreach ($order->addresses as $address)
                <div class="border border-gray-300 rounded p-4 bg-gray-50">
                    <h3 class="text-lg font-bold mb-2 text-gray-800">{{ ucfirst($address->type) }} Address</h3>
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
        <h2 class="text-xl font-semibold mb-4 text-gray-900">Items</h2>
        <table class="w-full border border-gray-300 rounded">
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="p-3 border-b border-gray-300">Product</th>
                <th class="p-3 border-b border-gray-300">Price</th>
                <th class="p-3 border-b border-gray-300">Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->items as $item)
                <tr class="border-t border-gray-300 text-center">
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
        <h2 class="text-xl font-semibold mb-4 text-gray-900">Payment Information</h2>
        <table class="w-full table-auto text-left border-collapse border border-gray-300 rounded">
            <thead>
            <tr class="bg-gray-100 text-gray-700 text-center">
                <th class="p-3 border-b border-gray-300">Method</th>
                <th class="p-3 border-b border-gray-300">Amount</th>
                <th class="p-3 border-b border-gray-300">Status</th>
                <th class="p-3 border-b border-gray-300">Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->payments as $payment)
                <tr class="border-t border-gray-300 text-center">
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
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Shipments ({{ $order->shipments->count() }})</h2>

            @foreach ($order->shipments as $index => $shipment)
                <div class="mb-6 border border-gray-300 rounded p-4 bg-gray-50">
                    <h3 class="font-bold text-lg mb-2 text-gray-800">Shipment #{{ $index + 1 }}</h3>
                    <table class="w-full table-auto text-left border-collapse">
                        <tbody>
                        <tr>
                            <th class="py-2 w-1/4 font-semibold border-b border-gray-300">Tracking Number:</th>
                            <td class="border-b border-gray-300">{{ $shipment->tracking_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2 font-semibold border-b border-gray-300">Carrier:</th>
                            <td class="border-b border-gray-300">{{ $shipment->carrier ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2 font-semibold border-b border-gray-300">Status:</th>
                            <td class="border-b border-gray-300">{{ ucfirst($shipment->status) ?? 'Pending' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2 font-semibold border-b border-gray-300">Shipping Method:</th>
                            <td class="border-b border-gray-300 uppercase">{{ $shipment->shipping_method ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2 font-semibold border-b border-gray-300">Shipping Cost:</th>
                            <td class="border-b border-gray-300">€{{ number_format($shipment->shipping_cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="py-2 font-semibold border-b border-gray-300">Shipped At:</th>
                            <td class="border-b border-gray-300">{{ optional($shipment->shipped_at)->format('Y-m-d H:i') ?? 'Not yet shipped' }}</td>
                        </tr>
                        <tr>
                            <th class="py-2 font-semibold">Delivered At:</th>
                            <td>{{ optional($shipment->delivered_at)->format('Y-m-d H:i') ?? 'Not yet delivered' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

</div>
