<div class="max-w-3xl mx-auto p-6 bg-black rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Checkout - Payment</h1>

    <section class="mb-8">
        <h2 class="text-lg font-semibold mb-2">Customer Information</h2>

        @if(!empty($customerInfo))
            <p><strong>Name:</strong> {{ $customerInfo['name'] ?? 'Guest' }}</p>
            <p><strong>Email:</strong> {{ $customerInfo['email'] ?? 'Not provided' }}</p>
            @if(!empty($customerInfo['address_line1']))
                <p><strong>Address:</strong> {{ $customerInfo['address_line1'] }} {{ $customerInfo['address_line2'] ?? '' }}</p>
                <p>{{ $customerInfo['city'] ?? '' }}, {{ $customerInfo['state'] ?? '' }} {{ $customerInfo['zip'] ?? '' }}</p>
                <p>{{ $customerInfo['country'] ?? '' }}</p>
            @endif
        @else
            <p>Guest checkout</p>
        @endif
    </section>

    <section>
        <h2 class="text-lg font-semibold mb-2">Select Payment Method</h2>

        {{-- Stripe Payment Button Component --}}
        <livewire:tenant.frontend.shopping.stripe-payment-button />
    </section>
</div>
