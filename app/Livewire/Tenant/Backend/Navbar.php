<?php

namespace App\Livewire\Tenant\Backend;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.tenant.backend.navbar');
    }
}
