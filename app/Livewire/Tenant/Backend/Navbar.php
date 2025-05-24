<?php

namespace App\Livewire\Tenant\Backend;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('tenant.login');
    }

    public function render()
    {
        return view('livewire.tenant.backend.navbar');
    }
}
