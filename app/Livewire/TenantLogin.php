<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class TenantLogin extends Component
{
    public $email;
    public $password;
    public $errorMessage;
    public bool $remember = false;

    public function loginTenant()
    {
        $user = User::where('email', $this->email)->first();

        if (! $user || ! Hash::check($this->password, $user->password)) {
            $this->addError('email', __('Invalid credentials.'));
            return;
        }

        if (! $user->is_active) {
            $this->addError('email', __('Your account is inactive. Please contact an admin for further assistance.'));
            return;
        }

        Auth::login($user, $this->remember);
        session()->regenerate();

        return redirect()->intended(route('tenant-dashboard.index'));
    }

    public function render()
    {
        return view('livewire.tenant-login');
    }
}
