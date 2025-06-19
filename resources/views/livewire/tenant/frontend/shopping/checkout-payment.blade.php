<div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-2xl shadow-md space-y-10">
    <h1 class="text-2xl font-bold text-gray-800">Checkout - Payment</h1>

    <!-- Customer Info -->
    <section class="space-y-3">
        <h2 class="text-lg font-semibold text-gray-700">Customer Information</h2>

        @if(!empty($customerInfo))
            <div class="text-sm text-gray-800 space-y-1">
                <p><strong>Name:</strong> {{ $customerInfo['name'] ?? 'Guest' }}</p>
                <p><strong>Email:</strong> {{ $customerInfo['email'] ?? 'Not provided' }}</p>

                @if(!empty($customerInfo['address_line1']))
                    <p><strong>Shipping Address:</strong></p>
                    <p>{{ $customerInfo['address_line1'] }} {{ $customerInfo['address_line2'] ?? '' }}</p>
                    <p>{{ $customerInfo['city'] ?? '' }}, {{ $customerInfo['state'] ?? '' }} {{ $customerInfo['zip'] ?? '' }}</p>
                    <p>{{ $customerInfo['country'] ?? '' }}</p>
                @endif

                @if(data_get($customerInfo, 'billing_different', false))
                    <div class="pt-2">
                        <p><strong>Billing Address:</strong> {{ data_get($customerInfo, 'billing_address_line1') }} {{ data_get($customerInfo, 'billing_address_line2') }}</p>
                        <p>{{ data_get($customerInfo, 'billing_city') }}, {{ data_get($customerInfo, 'billing_state') }} {{ data_get($customerInfo, 'billing_zip') }}</p>
                        <p>{{ data_get($customerInfo, 'billing_country') }}</p>
                    </div>
                @endif
            </div>
        @else
            <p class="text-gray-600">Guest checkout</p>
        @endif
    </section>

    <!-- Order Summary -->
    <section class="space-y-3">
        <h2 class="text-lg font-semibold text-gray-700">Order Summary</h2>

        <div class="text-sm text-gray-800 space-y-1">
            <p><strong>Cart Subtotal:</strong> €{{ number_format($cartTotal, 2) }}</p>
            <p><strong>Shipping:</strong> €{{ number_format($shippingCost, 2) }}</p>
            <p><strong>Tax (21% BTW):</strong> €{{ number_format($taxAmount, 2) }}</p>
        </div>

        <div class="pt-4 border-t border-gray-200 mt-4">
            <p class="text-xl font-bold text-gray-900"><strong>Total (incl. BTW):</strong> €{{ number_format($grandTotal, 2) }}</p>
        </div>
    </section>

    <!-- Payment Method -->
    <section class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-700">Select Payment Method</h2>

        <livewire:tenant.frontend.shopping.stripe-payment-button />

        @if (session('stockError'))
            <div class="text-red-600 font-semibold text-sm">
                {{ session('stockError') }}
            </div>
        @endif
    </section>
</div>
