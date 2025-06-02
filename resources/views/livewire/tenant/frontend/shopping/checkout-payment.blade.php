<div class="max-w-3xl mx-auto p-6 bg-black rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Checkout - Payment</h1>

    <section class="mb-8">
        <h2 class="text-lg font-semibold mb-2">Customer Information</h2>

        @if(!empty($customerInfo))
            <p><strong>Name:</strong> {{ $customerInfo['name'] ?? 'Guest' }}</p>
            <p><strong>Email:</strong> {{ $customerInfo['email'] ?? 'Not provided' }}</p>
            @if(!empty($customerInfo['address_line1']))
                <p><strong>Shipping Address:</strong> {{ $customerInfo['address_line1'] }} {{ $customerInfo['address_line2'] ?? '' }}</p>
                <p>{{ $customerInfo['city'] ?? '' }}, {{ $customerInfo['state'] ?? '' }} {{ $customerInfo['zip'] ?? '' }}</p>
                <p>{{ $customerInfo['country'] ?? '' }}</p>
            @endif
            @if(($customerInfo['billing_different' ?? false]))
                <p><strong>Billing Address:</strong> {{ $customerInfo['billing_address_line1'] }} {{ $customerInfo['billing_address_line2'] ?? '' }}</p>
                <p>{{ $customerInfo['billing_city'] ?? '' }}, {{ $customerInfo['billing_state'] ?? '' }} {{ $customerInfo['billing_zip'] ?? '' }}</p>
                <p>{{ $customerInfo['billing_country'] ?? '' }}</p>
            @endif
        @else
            <p>Guest checkout</p>
        @endif
    </section>

    <section class="mb-8">
        <h2 class="text-lg font-semibold mb-2">Order Summary</h2>
        <div class="text-white space-y-1">
            <p><strong>Cart Subtotal:</strong> €{{ number_format($cartTotal, 2) }}</p>
            <p><strong>Shipping:</strong> €{{ number_format($shippingCost, 2) }}</p>
            <p><strong>Tax (21% BTW):</strong> €{{ number_format($taxAmount, 2) }}</p>
        </div>
        <div class="mt-10">
            <p class="text-xl font-bold mt-2"><strong>Total (incl. BTW):</strong> €{{ number_format($grandTotal, 2) }}</p>
        </div>
    </section>


    <section>
        <h2 class="text-lg font-semibold mb-2">Select Payment Method</h2>
        <livewire:tenant.frontend.shopping.stripe-payment-button />
    </section>
</div>
