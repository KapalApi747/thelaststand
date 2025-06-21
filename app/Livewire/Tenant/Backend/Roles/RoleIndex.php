<?php

namespace App\Livewire\Tenant\Backend\Roles;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('t-dashboard-layout')]
class RoleIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    // Optional: Listen to events for refreshing the list
    protected $listeners = ['roleUpdated' => '$refresh', 'roleCreated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        // Optional: add guard so you don't delete certain roles
        if (in_array($role->name, ['admin', 'super-admin'])) {
            session()->flash('error', 'Cannot delete this role.');
            return;
        }

        $role->delete();
        session()->flash('message', 'Role deleted successfully.');
    }

    public function render()
    {
        $roles = Role::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.tenant.backend.roles.role-index', compact('roles'));
    }
}
