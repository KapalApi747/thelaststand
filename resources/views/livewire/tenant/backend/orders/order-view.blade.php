<div class="space-y-6">
    <h3 class="h3 font-semibold">Order #{{ $order->order_number }}</h3>

    <div class="flex justify-start">
        <a
            href=" {{ route('tenant-dashboard.order-edit', $order) }}"
            class="btn btn-primary"
        >
            Edit Order
        </a>
    </div>

    <div class="bg-white p-4 shadow rounded-xl">
        <h4 class="h4 font-bold mb-2 ">Customer Details</h4>
        <p class="text-black">Name: {{ $order->customer->name }}</p>
        <p class="text-black">Email: {{ $order->customer->email }}</p>
        <p class="text-black">Phone: {{ $order->customer->phone }}</p>
    </div>

    <div class="bg-white p-4 shadow rounded-xl">
        <h4 class="h4 font-bold mb-2">Order Summary</h4>
        <p class="text-black">Status: <span class="font-medium">{{ ucfirst($order->status) }}</span></p>
        <p class="text-black">Total: €{{ number_format($order->total_amount, 2) }}</p>
        <p class="text-black">Tax: €{{ number_format($order->tax_amount, 2) }}</p>
        <p class="text-black">Shipping: €{{ number_format($order->shipping_cost, 2) }}</p>
    </div>

    <div class="bg-white p-4 shadow rounded-xl">
        <h4 class="h4 font-bold mb-2">Order Items</h4>
        <ul>
            @foreach($order->items as $item)
                <li class="border-b py-2">
                    <strong>{{ $item->product_name }}</strong>
                    <div class="text-sm text-gray-600">
                        Quantity: {{ $item->quantity }} | Price: €{{ number_format($item->price, 2) }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    @if($order->payments->isNotEmpty())
        <div class="bg-white p-4 shadow rounded-xl">
            <h4 class="h4 font-bold mb-2">Payments</h4>
            <ul>
                @foreach($order->payments as $payment)
                    <li class="border-b py-2">
                        Method: {{ ucfirst($payment->payment_method) }}
                        <br>
                        Amount: €{{ number_format($payment->amount, 2) }}
                        <br>
                        Status: {{ ucfirst($payment->status) }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $shipping = $order->addresses->firstWhere('type', 'shipping');
        $billing = $order->addresses->firstWhere('type', 'billing');
    @endphp

    @if($shipping || $billing)
        <div class="bg-white p-4 shadow rounded-xl">
            <h4 class="h4 font-bold mb-4">Addresses</h4>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-black font-semibold">Shipping Address</p>
                    @if($shipping)
                        <p class="text-black">{{ $shipping->full_name }}</p>
                        <p class="text-black">{{ $shipping->address_line1 }}</p>
                        @if($shipping->address_line2)
                            <p class="text-black">{{ $shipping->address_line2 }}</p>
                        @endif
                        <p class="text-black">{{ $shipping->city }}, {{ $shipping->state }} {{ $shipping->zip }}</p>
                        <p class="text-black">{{ $shipping->country }}</p>
                        <p class="text-black">{{ $shipping->phone }}</p>
                    @else
                        <p class="text-gray-500 italic">Same as billing address</p>
                    @endif
                </div>

                <div>
                    <p class="text-black font-semibold">Billing Address</p>
                    @if($billing)
                        <p class="text-black">{{ $billing->full_name }}</p>
                        <p class="text-black">{{ $billing->address_line1 }}</p>
                        @if($billing->address_line2)
                            <p class="text-black">{{ $billing->address_line2 }}</p>
                        @endif
                        <p class="text-black">{{ $billing->city }}, {{ $billing->state }} {{ $billing->zip }}</p>
                        <p class="text-black">{{ $billing->country }}</p>
                        <p class="text-black">{{ $billing->phone }}</p>
                    @else
                        <p class="text-gray-500 italic">Same as shipping address</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
