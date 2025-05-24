<?php

namespace App\Livewire\Tenant\Backend;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class TenantDashboard extends Component
{
    public function render()
    {
        return view('livewire.tenant.backend.tenant-dashboard');
    }
}
