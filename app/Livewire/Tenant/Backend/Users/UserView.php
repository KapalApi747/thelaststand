<?php

namespace App\Livewire\Tenant\Backend\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class UserView extends Component
{
    public User $user;

    public function render()
    {
        $user = User::with(['roles'])->findOrFail($this->user->id);

        return view('livewire.tenant.backend.users.user-view', [
            'user' => $user
        ]);
    }
}
