<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class TenantLogin extends Component
{
    public $email;
    public $password;
    public $errorMessage;
    public bool $remember = false;

    public function testButton(): void
    {
        $this->errorMessage = 'Test button clicked!';
    }

    public function loginTenant()
    {
        $credentials = $this->only(['email', 'password']);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('tenant-dashboard.index')); // or wherever
        }

        $this->addError('email', __('Invalid credentials.'));
    }

    // Render the login form view
    public function render()
    {
        return view('livewire.tenant-login');
    }
}
