<div class="max-w-2xl mx-auto mt-6space-y-6 bg-white p-8 rounded-lg shadow-md">

    <h1 class="text-2xl font-bold text-gray-800 text-center">Your Cart</h1>

    <div class="my-4">
        <a
            href="{{ route('shop.shop-products') }}"
            class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition"
        >
            ← Back to Shop
        </a>
    </div>

    @forelse ($cart as $itemKey => $item)
        @php
            $stock = 0;

            if (isset($item['variant_id'])) {
                $variant = \App\Models\ProductVariant::find($item['variant_id']);
                $stock = $variant?->stock ?? 0;
            } else {
                $product = \App\Models\Product::find($item['product_id']);
                $stock = $product?->stock ?? 0;
            }
        @endphp

        <div class="flex items-center justify-between border border-gray-200 p-4 rounded-lg bg-gray-50">
            <div class="flex items-center gap-4">
                <img src="{{ asset('tenant' . tenant()->id . '/' . $item['image']) }}" class="w-14 h-14 rounded-md object-cover shadow-sm" />
                <div>
                    <div class="font-semibold text-gray-800">{{ $item['name'] }}</div>
                    <div class="text-sm text-gray-500">€{{ number_format($item['price'], 2) }}</div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input
                    type="number"
                    min="1"
                    max="{{ $stock }}"
                    inputmode="numeric"
                    pattern="\d+"
                    @disabled($stock === 0)
                    wire:change="updateProductQuantity('{{ $itemKey }}', $event.target.value)"
                    value="{{ $item['quantity'] }}"
                    oninput="if (this.value === '' || this.value === '0') this.value = 1; this.value = this.value.replace(/[^0-9]/g, '')"
                    class="w-16 border border-gray-300 rounded-md text-center text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >

                <button wire:click="removeFromCart('{{ $itemKey }}')" class="text-red-600 hover:underline text-sm font-medium cursor-pointer">
                    Remove
                </button>
            </div>
        </div>
    @empty
        <p class="text-gray-600">Your cart is empty.</p>
    @endforelse

    @if (count($cart) > 0)
        <div class="space-y-1 text-right text-gray-800 mt-6">
            <p class="font-medium">
                BTW Tax (21%): <span class="text-gray-700">€{{ number_format($taxAmount, 2) }}</span>
            </p>
            <p class="font-semibold text-lg">
                Subtotal (incl. BTW): <span class="text-gray-900">€{{ number_format($cartTotal, 2) }}</span>
            </p>

            @if ($this->checkForStockIssues())
                <p class="text-red-600 mt-2 font-semibold text-sm">
                    Some items in your cart are out of stock or exceed available quantity. Please adjust before checkout.
                </p>
            @endif
        </div>

        <div class="text-right mt-4">
            <button
                wire:click.prevent="{{ $this->checkForStockIssues() ? '' : 'goToCheckout' }}"
                {{ $this->checkForStockIssues() ? 'disabled' : '' }}
                class="inline-block bg-gray-900 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
            >
                Proceed to Checkout
            </button>
        </div>
    @endif

    @if (session('stockError'))
        <div class="text-red-600 font-semibold mt-4 text-sm">
            {{ session('stockError') }}
        </div>
    @endif
</div>
