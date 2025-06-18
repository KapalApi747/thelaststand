<?php

namespace App\Livewire\Admin\Backend\Tenants;

use App\Models\Tenant;
use App\Services\TenantDeletionService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('c-dashboard-layout')]
class TenantProfilePage extends Component
{
    use WithPagination;

    public function deleteTenant($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        try {
            app(TenantDeletionService::class)->deleteTenant($tenant);
            session()->flash('message', 'Tenant deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Tenant deletion failed', ['error' => $e->getMessage()]);
            session()->flash('message', 'Failed to delete tenant. Check logs.');
        }
    }

    public function toggleStatus($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $tenant->profile->store_status = $tenant->profile->store_status === 'active'
            ? 'inactive'
            : 'active';

        $tenant->profile->save();

        session()->flash('message', 'Tenant status updated successfully.');
    }

    public function render()
    {
        $tenants = Tenant::with('profile', 'domains')
            ->withTrashed()
            ->paginate(10);

        return view('livewire.admin.backend.tenants.tenant-profile-page', [
            'tenants' => $tenants,
        ]);
    }
}
