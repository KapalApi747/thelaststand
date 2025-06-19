<div class="p-6 bg-white rounded shadow">
    <div>
        <h3 class="h3 font-semibold mb-6">Customer Details: {{ $customer->name }}</h3>

        <section class="mb-6">
            <h3 class="font-semibold text-lg mb-2">Basic Information</h3>
            <p class="text-gray-800"><strong>Email:</strong> {{ $customer->email }}</p>
            <p class="text-gray-800"><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p class="text-gray-800"><strong>Status:</strong> {{ $customer->is_active ? 'Active' : 'Inactive' }}</p>
            <p class="text-gray-800"><strong>Joined At:</strong> {{ optional($customer->created_at)->format('Y-m-d') }}</p>
        </section>

        <section class="mb-6">
            <h3 class="font-semibold text-lg mb-2">Order Summary</h3>
            <p class="text-gray-800"><strong>Total Orders:</strong> {{ $customer->orders->count() }}</p>
            <p class="text-gray-800"><strong>Total Spent:</strong> €{{ number_format($customer->orders->sum('total_amount'), 2) }}</p>
        </section>

        <section class="mb-6 grid grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-lg mb-2">Shipping Address</h3>
                @if($shipping = $customer->addresses->firstWhere('type', 'shipping'))
                    <p class="text-gray-800">{{ $shipping->address_line1 }}</p>
                    <p class="text-gray-800">{{ $shipping->address_line2 }}</p>
                    <p class="text-gray-800">{{ $shipping->city }}, {{ $shipping->state }} {{ $shipping->zip }}</p>
                    <p class="text-gray-800">{{ $shipping->country }}</p>
                @else
                    <p class="text-gray-800">No shipping address set.</p>
                @endif
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-2">Billing Address</h3>
                @if($billing = $customer->addresses->firstWhere('type', 'billing'))
                    <p class="text-gray-800">{{ $billing->address_line1 }}</p>
                    <p class="text-gray-800">{{ $billing->address_line2 }}</p>
                    <p class="text-gray-800">{{ $billing->city }}, {{ $billing->state }} {{ $billing->zip }}</p>
                    <p class="text-gray-800">{{ $billing->country }}</p>
                @else
                    <p class="text-gray-800">No billing address set.</p>
                @endif
            </div>
        </section>

        <section>
            <h3 class="font-semibold text-lg mb-2">Review History</h3>
            @if($customer->reviews->isEmpty())
                <p class="text-gray-800">No reviews written.</p>
            @else
                <ul class="space-y-3 max-h-48 overflow-y-auto">
                    @foreach($customer->reviews as $review)
                        <li class="border rounded p-2">
                            <p class="text-gray-800"><strong>Product ID:</strong> {{ $review->product_id }}</p>
                            <p class="text-gray-800"><strong>Rating:</strong> {{ $review->rating }}/5</p>
                            <p class="text-gray-800"><strong>Comment:</strong> {{ $review->comment }}</p>
                            <p class="text-sm text-gray-600">Reviewed on {{ optional($review->created_at)->format('Y-m-d') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <div class="mt-6">
            <a href="{{ route('tenant-dashboard.customer-index') }}" class="btn btn-primary">← Back to customers list</a>
        </div>
    </div>

</div>
