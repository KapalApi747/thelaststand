<?php

namespace App\Livewire\Admin\Backend\Tenants;

use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('c-dashboard-layout')]
class EditTenantProfile extends Component
{
    public Tenant $tenant;
    public $profile;

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
        $this->profile = $tenant->profile->toArray();
    }

    protected function rules()
    {
        return [
            'profile.email' => 'nullable|email',
            'profile.phone' => 'nullable|string|max:20',
            'profile.address' => 'nullable|string',
            'profile.city' => 'nullable|string|max:100',
            'profile.state' => 'nullable|string|max:100',
            'profile.zip' => 'nullable|string|max:20',
            'profile.country' => 'nullable|string|max:100',
            'profile.vat_id' => 'nullable|string|max:100',
            'profile.business_description' => 'nullable|string|max:1000',
            'profile.store_status' => 'required|in:active,inactive',
        ];
    }

    public function saveProfile()
    {
        $this->validate();
        $this->tenant->profile->update($this->profile);
        session()->flash('message', 'Profile updated successfully.');
    }
    public function render()
    {
        return view('livewire.admin.backend.tenants.edit-tenant-profile');
    }
}
