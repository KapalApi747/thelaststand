<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerLogin extends Component
{
    public $email;
    public $password;
    public $errorMessage;
    public bool $remember = false;

    public function loginCustomer()
    {
        $customer = Customer::where('email', $this->email)->first();

        if (! $customer || ! Hash::check($this->password, $customer->password)) {
            $this->addError('email', __('Invalid credentials.'));
            return;
        }

        if (! $customer->is_active) {
            $this->addError('email', __('Your account is inactive. Please contact support.'));
            return;
        }

        Auth::guard('customer')->login($customer, $this->remember);

        session()->regenerate();

        return redirect()->intended(route('shop.shop-products'));
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-login');
    }
}
