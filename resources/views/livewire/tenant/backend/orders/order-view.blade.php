<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Order #{{ $order->order_number }}</h1>

    <div class="bg-white p-4 shadow rounded-xl">
        <h2 class="font-bold mb-2">Customer Details</h2>
        <p>Name: {{ $order->customer->name }}</p>
        <p>Email: {{ $order->customer->email }}</p>
        <p>Phone: {{ $order->customer->phone }}</p>
    </div>

    <div class="bg-white p-4 shadow rounded-xl">
        <h2 class="font-bold mb-2">Order Summary</h2>
        <p>Status: <span class="font-medium">{{ ucfirst($order->status) }}</span></p>
        <p>Total: ${{ number_format($order->total_amount, 2) }}</p>
        <p>Tax: ${{ number_format($order->tax_amount, 2) }}</p>
        <p>Shipping: ${{ number_format($order->shipping_cost, 2) }}</p>
    </div>

    <div class="bg-white p-4 shadow rounded-xl">
        <h2 class="font-bold mb-2">Order Items</h2>
        <ul>
            @foreach($order->items as $item)
                <li class="border-b py-2">
                    <strong>{{ $item->product_name }}</strong>
                    <div class="text-sm text-gray-600">
                        Quantity: {{ $item->quantity }} | Price: ${{ number_format($item->price, 2) }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    @if($order->payments->isNotEmpty())
        <div class="bg-white p-4 shadow rounded-xl">
            <h2 class="font-bold mb-2">Payments</h2>
            <ul>
                @foreach($order->payments as $payment)
                    <li class="border-b py-2">
                        Method: {{ ucfirst($payment->payment_method) }}<br>
                        Amount: ${{ number_format($payment->amount, 2) }}<br>
                        Status: {{ ucfirst($payment->status) }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($order->addresses->isNotEmpty())
        <div class="bg-white p-4 shadow rounded-xl">
            <h2 class="font-bold mb-2">Addresses</h2>
            @foreach($order->addresses as $address)
                <div class="mb-4">
                    <p class="font-semibold">{{ ucfirst($address->type) }} Address</p>
                    <p>{{ $address->full_name }}</p>
                    <p>{{ $address->address_line1 }}</p>
                    @if($address->address_line2)
                        <p>{{ $address->address_line2 }}</p>
                    @endif
                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                    <p>{{ $address->country }}</p>
                    <p>{{ $address->phone }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
