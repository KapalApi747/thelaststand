<?php

namespace App\Livewire\Admin\Backend\Tenants;

use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('c-dashboard-layout')]
class TenantProfilePage extends Component
{
    use WithPagination;

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
