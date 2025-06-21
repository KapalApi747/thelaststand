<?php

namespace App\Livewire\Tenant\Backend\Roles;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('t-dashboard-layout')]
class RoleCreation extends Component
{
    public $name = '';
    public $permissions = [];
    public $allPermissions = [];

    public function mount()
    {
        $this->allPermissions = Permission::orderBy('name')->get();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name|max:50',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }

    public function submit()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->permissions);

        session()->flash('message', 'Role created successfully.');
        $this->reset(['name', 'permissions']);
        return redirect()->route('tenant-dashboard.role-index');


    }

    public function render()
    {
        return view('livewire.tenant.backend.roles.role-creation');
    }
}
