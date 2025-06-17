<?php

namespace App\Livewire\Admin\Backend\Tenants;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('c-dashboard-layout')]
class ViewTenantProfile extends Component
{
    public Tenant $tenant;
    public $profile;

    public $periodStart;
    public $periodEnd;
    public $loading = false;
    public $errorMessage = null;

    public $totalRevenue = 0;
    public $orderCount = 0;
    public $averageOrderValue = 0;

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
        $this->profile = $tenant->profile;

        $this->periodStart = now()->startOfMonth()->toDateString();
        $this->periodEnd = now()->toDateString();

        $this->loadStats();
    }

    public function loadStats()
    {
        $this->loading = true;
        $this->errorMessage = null;

        try {
            $tenant = $this->tenant;

            $this->profile = $tenant->profile;

            config(['database.connections.mysql.database' => $tenant->tenancy_db_name]);

            DB::purge('mysql');
            DB::reconnect('mysql');

            $query = DB::connection('mysql')->table('orders')
                ->where('status', 'completed');

            if ($this->periodStart) {
                $query->where('created_at', '>=', $this->periodStart);
            }
            if ($this->periodEnd) {
                $query->where('created_at', '<=', $this->periodEnd);
            }

            $this->totalRevenue = $query->sum('total_amount');
            $this->orderCount = $query->count();
            $this->averageOrderValue = $this->orderCount > 0
                ? round($this->totalRevenue / $this->orderCount, 2)
                : 0;

        } catch (\Exception $e) {
            $this->errorMessage = "Failed to load tenant income: " . $e->getMessage();
            $this->totalRevenue = 0;
            $this->averageOrderValue = 0;
            $this->orderCount = 0;
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.backend.tenants.view-tenant-profile');
    }
}
