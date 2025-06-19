<?php

namespace App\Livewire\Tenant\Backend\Payouts;

use App\Models\Order;
use App\Models\Payout;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class TenantPayoutIndex extends Component
{
    public $payouts = [];

    public function mount()
    {
        $tenantId = tenant('id');

        $this->payouts = Payout::where('tenant_id', $tenantId)
            ->orderBy('paid_at')
            ->get()
            ->map(function ($payout) {
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
