<div class="p-6 bg-white rounded shadow">

    <h3 class="h3 font-bold mb-4">Your Payouts</h3>

    @if ($payouts->isEmpty())
        <p class="text-gray-500">You have no payouts yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Order Count</th>
                    <th class="border p-2">Orders Total</th>
                    <th class="border p-2">Payout Total</th>
                    <th class="border p-2">Date Paid</th>
                    <th class="border p-2 text-center">Actions</th>
                </tr>
                </thead>
                <tbody class="text-sm text-gray-800">
                @foreach ($payouts as $payout)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2">{{ $payout->id }}</td>
                        <td class="border p-2">{{ $payout->orders->count() }}</td>
                        <td class="border p-2">
                            ${{ number_format($payout->orders->sum('total_amount'), 2) }}
                        </td>
                        <td class="border p-2">
                            ${{ number_format($payout->amount, 2) }}
                        </td>
                        <td class="border p-2">
                            {{ $payout->paid_at->format('Y-m-d') }}
                        </td>
                        <td class="border p-2 text-center">
                            <a href="{{ route('tenant-dashboard.tenant-payout-view', $payout) }}"
                               class="text-blue-600"><i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
