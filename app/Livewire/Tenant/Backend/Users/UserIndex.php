<?php

namespace App\Livewire\Tenant\Backend\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('t-dashboard-layout')]
class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';
    public $is_active = '';

    public $name;
    public $email;

    public $pagination = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';


    public function render()
    {
        $users = User::query()
            ->with(['roles'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->role, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('roles.id', $this->role);
                });
            })
            ->when($this->is_active !== '', function ($query) {
                $query->where('is_active', $this->is_active);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->pagination);

        return view('livewire.tenant.backend.users.user-index', [
            'users' => $users,
            'roles' => Role::all()
        ]);
    }
}
