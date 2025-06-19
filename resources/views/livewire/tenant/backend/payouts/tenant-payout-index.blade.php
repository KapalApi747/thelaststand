<div class="p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Your Payouts</h2>

    @if ($payouts->isEmpty())
        <p>You have no payouts yet.</p>
    @else
        <ul class="space-y-6">
            @foreach ($payouts as $payout)
                <li class="border p-4 rounded">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Payout #{{ $payout->id }} - {{ $payout->payout_date->format('Y-m-d') }}</span>
                        <span class="text-green-700 font-bold">${{ number_format($payout->amount, 2) }}</span>
                    </div>
                    <div>
                        <strong>Orders included:</strong>
                        @if($payout->orders->isEmpty())
                            <p>No orders found for this payout.</p>
                        @else
                            <ul class="list-disc pl-5">
                                @foreach($payout->orders as $order)
                                    <li>Order #{{ $order->id }} - ${{ number_format($order->total, 2) }} - {{ $order->status }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
