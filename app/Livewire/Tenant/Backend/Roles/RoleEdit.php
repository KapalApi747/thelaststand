<?php

namespace App\Livewire\Tenant\Backend\Roles;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('t-dashboard-layout')]
class RoleEdit extends Component
{
    public Role $role;
    public $roleId;
    public $name;
    public $permissions = [];

    public $allPermissions;

    public function mount(Role $role)
    {
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('name')->toArray();
        $this->allPermissions = Permission::all();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }

    public function save()
    {
        $this->validate();

        $role = Role::findOrFail($this->roleId);
        $role->name = $this->name;
        $role->save();

        // Sync permissions by name
        $role->syncPermissions($this->permissions);

        session()->flash('message', 'Role updated successfully.');
    }

    public function render()
    {
        return view('livewire.tenant.backend.roles.role-edit', [
            'allPermissions' => $this->allPermissions,
        ]);
    }
}
