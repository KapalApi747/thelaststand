<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerRegistration extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('customers', 'email')],
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Try to find an existing customer
        $existingCustomer = Customer::where('email', $this->email)->first();

        if ($existingCustomer) {
            if ($existingCustomer->password) {
                // Already registered
                $this->addError('email', 'This email is already registered. Please log in.');
                return;
            }

            // Guest: upgrade to full account
            $existingCustomer->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'is_active' => true,
            ]);

            Auth::guard('customer')->login($existingCustomer);
            $existingCustomer->sendEmailVerificationNotification();

            return redirect()->route('customer-verification.notice');
        }

        // New registration
        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?? null,
            'password' => Hash::make($this->password),
            'is_active' => true,
        ]);

        Auth::guard('customer')->login($customer);
        $customer->sendEmailVerificationNotification();

        return redirect()->route('customer-verification.notice');
    }


    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-registration');
    }
}
