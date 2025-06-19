<?php

namespace App\Livewire\Tenant\Backend\Payouts;

use App\Models\Order;
use App\Models\Payout;
use Livewire\Component;

class TenantPayoutIndex extends Component
{
    public $payouts = [];

    public function mount()
    {
        $tenantId = tenant('id'); // however you get tenant id

        // 1. Get payouts from central DB for this tenant
        $this->payouts = Payout::where('tenant_id', $tenantId)
            ->orderByDesc('payout_date')
            ->get()
            ->map(function ($payout) {
                // 2. For each payout, get tenant orders with this payout_id
                $orders = Order::where('payout_id', $payout->id)->get();
                $payout->orders = $orders;
                return $payout;
            });
    }

    public function render()
    {
        return view('livewire.tenant.backend.payouts.tenant-payout-index');
    }
}
