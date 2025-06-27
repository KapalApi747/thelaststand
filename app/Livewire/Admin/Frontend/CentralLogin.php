<?php

namespace App\Livewire\Admin\Frontend;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('central-layout')]
class CentralLogin extends Component
{
    public $email;
    public $password;
    public $errorMessage;
    public bool $remember = false;

    public function loginCentral()
    {
        $credentials = $this->only(['email', 'password']);

        if (Auth::guard('central')->attempt($credentials)) {
            session()->regenerate();
            return redirect()->intended(route('home'));
        }

        $this->addError('email', __('Invalid credentials.'));
    }
    public function render()
    {
        return view('livewire.admin.frontend.central-login');
    }
}
