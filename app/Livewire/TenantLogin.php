<?php

namespace App\Livewire;

use App\Models\Customer;
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
        // Try to find user by email
        $user = User::where('email', $this->email)->first();

        if ($user) {
            if (! Hash::check($this->password, $user->password)) {
                $this->addError('email', __('Invalid credentials.'));
                return;
            }

            if (! $user->is_active) {
                $this->addError('email', __('Your account is inactive. Please contact an admin for assistance.'));
                return;
            }

            Auth::guard('web')->login($user, $this->remember);
            session()->regenerate();

            return redirect()->intended(route('tenant-homepage'));
        }

        // Try to find customer by email
        $customer = Customer::where('email', $this->email)->first();

        if ($customer) {
            if (! $customer->password || ! Hash::check($this->password, $customer->password)) {
                $this->addError('email', __('Invalid credentials.'));
                return;
            }

            if (! $customer->is_active) {
                $this->addError('email', __('Your customer account is inactive.'));
                return;
            }

            Auth::guard('customer')->login($customer, $this->remember);
            session()->regenerate();

            return redirect()->intended(route('shop.shop-products'));
        }

        $this->addError('email', __('Invalid credentials.'));
    }


    public function render()
    {
        return view('livewire.tenant-login');
    }
}
