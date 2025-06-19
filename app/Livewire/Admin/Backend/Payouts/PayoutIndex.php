<?php

namespace App\Livewire\Admin\Backend\Payouts;

use App\Models\Payout;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('c-dashboard-layout')]
class PayoutIndex extends Component
{
    use WithPagination;

    public $tenantPayouts = [];
    public $existingPayouts = [];

    public function mount()
    {
        $this->loadExistingPayouts();
        $this->tenantPayouts = $this->calculateTenantPayouts();
    }

    protected function calculateTenantPayouts()
    {
        $result = [];

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            try {
                $tenantConnectionName = 'tenant_dynamic_' . $tenant->id;

                config([
                    "database.connections.$tenantConnectionName" => array_merge(
                        config('database.connections.mysql'),
                        ['database' => $tenant->tenancy_db_name]
                    ),
                ]);

                $connection = DB::connection($tenantConnectionName);

                $eligibleOrders = $connection->table('orders')
                    ->whereIn('status', ['completed', 'paid'])
                    ->whereNull('payout_id');

                $totalOrdersAmount = $eligibleOrders->sum('total_amount');
                $orderCount = $eligibleOrders->count(); // 👈 count eligible orders

                $platformFee = round($totalOrdersAmount * 0.05, 2);
                $tenantEarnings = $totalOrdersAmount - $platformFee;

                $payout = $this->existingPayouts[$tenant->id] ?? null;

                $payoutStatus = $payout['status'] ?? null;
                $payoutId = $payout['id'] ?? null;

                if (!$payout && $tenantEarnings > 0) {
                    $payoutStatus = null;
                    $payoutId = null;
                }

                $result[] = [
                    'tenant_id' => $tenant->id,
                    'tenant_name' => $tenant->store_name,
                    'total_orders_amount' => $totalOrdersAmount,
                    'platform_fee' => $platformFee,
                    'tenant_earnings' => $tenantEarnings,
                    'order_count' => $orderCount,
                    'payout_status' => $payoutStatus,
                    'payout_id' => $payoutId,
                ];

                DB::disconnect($tenantConnectionName);

            } catch (\Exception $e) {
                $result[] = [
                    'tenant_id' => $tenant->id,
                    'tenant_name' => $tenant->name,
                    'error' => 'Failed to query orders: ' . $e->getMessage(),
                ];
            }
        }

        return $result;
    }

    protected function loadExistingPayouts()
    {
        $this->existingPayouts = Payout::whereIn('status', ['pending'])->get()->keyBy('tenant_id')->toArray();
    }

    public function createPayout($tenantId)
    {
        $tenantPayout = Payout::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->first();

        if ($tenantPayout) {
            session()->flash('message', ['type' => 'warning', 'text' => 'Payout already pending.']);
            return;
        }

        $tenantData = collect($this->tenantPayouts)->firstWhere('tenant_id', $tenantId);

        if (!$tenantData || !isset($tenantData['tenant_earnings'])) {
            session()->flash('message', ['type' => 'error', 'text' => 'Could not calculate payout amount.']);
            return;
        }

        $payout = Payout::create([
            'tenant_id' => $tenantId,
            'amount' => $tenantData['tenant_earnings'],
            'status' => 'pending',
        ]);

        $this->loadExistingPayouts();
        $this->tenantPayouts = $this->calculateTenantPayouts();

        session()->flash('message', ['type' => 'success', 'text' => 'Payout created and pending approval.']);
    }

    public function approvePayout($payoutId)
    {
        $payout = Payout::findOrFail($payoutId);
        $payout->status = 'paid';
        $payout->paid_at = now();
        $payout->save();

        // Mark all unpaid orders as included in this payout
        $tenant = Tenant::find($payout->tenant_id);
        $tenantConnectionName = 'tenant_dynamic_' . $tenant->id;

        config([
            "database.connections.$tenantConnectionName" => array_merge(
                config('database.connections.mysql'),
                ['database' => $tenant->tenancy_db_name]
            ),
        ]);

        DB::connection($tenantConnectionName)
            ->table('orders')
            ->whereIn('status', ['completed', 'paid'])
            ->whereNull('payout_id')
            ->update(['payout_id' => $payout->id]);

        DB::disconnect($tenantConnectionName);

        $this->loadExistingPayouts();
        $this->tenantPayouts = $this->calculateTenantPayouts();

        session()->flash('message', ['type' => 'success', 'text' => 'Payout approved and marked as paid.']);
    }

    public function rejectPayout($payoutId)
    {
        $payout = Payout::findOrFail($payoutId);
        $payout->delete();

        $this->loadExistingPayouts();
        $this->tenantPayouts = $this->calculateTenantPayouts();

        session()->flash('message', ['type' => 'warning', 'text' => 'Payout rejected and deleted.']);
    }

    public function render()
    {
        $tenants = Tenant::query()->paginate(10);

        $payouts = $tenants->map(function ($tenant) {
            return [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
            ];
        });

        return view('livewire.admin.backend.payouts.payout-index', [
            'payouts' => $payouts,
            'tenants' => $tenants,
        ]);
    }
}
