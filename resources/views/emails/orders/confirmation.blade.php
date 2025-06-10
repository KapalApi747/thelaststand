<x-mail::message>
    # Order Confirmation

    Hi {{ $order->customer->name }},

    Thanks for your order #{{ $order->order_number }} placed on {{ $order->created_at->format('F j, Y') }}.

    ---

    ## Order Summary

    @foreach ($order->items as $item)
        - **{{ $item->product_name }}** × {{ $item->quantity }} — €{{ number_format($item->price, 2) }}
    @endforeach

    **Total:** €{{ number_format($order->total_amount, 2) }}

    ---

    <x-mail::button :url="route('shop.shop-products', $order->id)" class="mb-6">
        View Your Order
    </x-mail::button>

    We will ship the items to you as soon as possible!

    If you have any questions, feel free to reply to this email.

    Kind regards,

    {{ tenant()->store_name }}
</x-mail::message>
