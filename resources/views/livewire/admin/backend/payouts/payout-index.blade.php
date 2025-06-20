<div class="p-6">
    <h3 class="h3 font-bold mb-4">Tenant Payouts</h3>

    @if(session('message'))
        @php
            $msg = session('message');
            $colors = [
                'success' => 'green',
                'warning' => 'yellow',
                'error' => 'red',
            ];
            $color = $colors[$msg['type']] ?? 'blue';
        @endphp
        <div class="mb-4 p-3 rounded text-white bg-{{ $color }}-600">
            {{ $msg['text'] }}
        </div>
    @endif

    <table class="min-w-full border rounded shadow bg-white">
        <thead>
        <tr class="bg-gray-100 text-center text-black">
            <th class="border border-gray-300 p-2">Store Name</th>
            <th class="border border-gray-300 p-2">Total Orders Amount</th>
            <th class="border border-gray-300 p-2">Platform Fee (5%)</th>
            <th class="border border-gray-300 p-2">Store Earnings</th>
            <th class="border border-gray-300 p-2">Orders Count</th>
            <th class="border border-gray-300 p-2">Status</th>
            <th class="border border-gray-300 p-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if (count($tenantPayouts) > 0)
            @foreach ($tenantPayouts as $payout)
                <tr class="text-center">
                    <td class="border border-gray-300 p-2">{{ $payout['tenant_name'] }}</td>
                    <td class="border border-gray-300 p-2">
                        €{{ number_format($payout['total_orders_amount'] ?? 0, 2) }}</td>
                    <td class="border border-gray-300 p-2">€{{ number_format($payout['platform_fee'] ?? 0, 2) }}</td>
                    <td class="border border-gray-300 p-2">€{{ number_format($payout['tenant_earnings'] ?? 0, 2) }}</td>
                    <td class="border border-gray-300 p-2">{{ $payout['order_count'] ?? 0 }}</td>
                    <td class="border border-gray-300 p-2">
                        @if (isset($payout['error']))
                            <span class="text-red-600">Error</span>
                        @elseif($payout['tenant_earnings'] > 0)
                            <span class="text-yellow-600">Ready</span>
                        @else
                            <span class="text-green-600">Paid</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 p-2 space-x-2">
                        @if(isset($payout['error']))
                            <span class="text-red-600">N/A</span>
                        @elseif((!isset($payout['payout_status']) || $payout['payout_status'] === null)
                            && isset($payout['tenant_earnings'])
                            && $payout['tenant_earnings'] > 0
                        )
                            <button wire:click="createPayout('{{ $payout['tenant_id'] }}')"
                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                Create Payout
                            </button>
                        @elseif($payout['payout_status'] === 'pending')
                            <button wire:click="approvePayout({{ $payout['payout_id'] }})"
                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                Approve
                            </button>
                            <button wire:click="rejectPayout({{ $payout['payout_id'] }})"
                                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                Reject
                            </button>
                        @elseif($payout['payout_status'] === 'paid')
                            <span class="text-green-600 font-semibold">Paid</span>
                        @elseif($payout['tenant_earnings'] == 0)
                            <span class="text-gray-400 italic">No payout available</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="7" class="border border-gray-300 p-2">No tenants found for payouts. Create a new tenant
                    first!
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="mt-4">
        {{ $tenants->links() }}
    </div>
</div>
