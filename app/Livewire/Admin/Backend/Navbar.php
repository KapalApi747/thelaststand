<?php

namespace App\Livewire\Admin\Backend;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public function logout()
    {
        Auth::guard('central')->logout();
        session()->regenerateToken();

        return redirect()->route('central-login');
    }

    public function render()
    {
        return view('livewire.admin.backend.navbar');
    }
}
