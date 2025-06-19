<div class="max-w-xl mx-auto mt-6 bg-white rounded-2xl shadow-md p-12 text-center space-y-6">
    <h1 class="text-3xl font-bold text-green-600">Payment Successful!</h1>

    @if ($message)
        <p class="text-gray-700 text-lg">{{ $message }}</p>
    @endif

    <p class="text-gray-600">Your order has been received and is currently being processed.</p>
    <p class="text-gray-600">You will receive a confirmation email shortly with your order details.</p>

    <a href="{{ route('shop.shop-products') }}"
       class="inline-block mt-6 text-white bg-teal-600 hover:bg-teal-700 px-6 py-2 rounded-full transition-colors duration-300">
        Back to Shop
    </a>
</div>
