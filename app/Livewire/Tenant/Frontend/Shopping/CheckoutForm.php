<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CheckoutForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address_line1 = '';
    public string $address_line2 = '';
    public string $city = '';
    public string $state = '';
    public string $zip = '';
    public string $country = '';

    public bool $loggedInCustomer = false;
    public bool $billingDifferent = false;

    public
        $billing_address_line1,
        $billing_address_line2,
        $billing_city,
        $billing_state,
        $billing_zip,
        $billing_country;

    public bool $askForAccount = false;
    public string $password = '';
    public string $password_confirmation = '';

    public bool $showLoginButton = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:255',

            'billing_address_line1' => $this->billingDifferent ? 'required|string|max:255' : 'nullable',
            'billing_address_line2' => 'nullable|string|max:255',
            'billing_city' => $this->billingDifferent ? 'required|string|max:100' : 'nullable',
            'billing_state' => $this->billingDifferent ? 'required|string|max:100' : 'nullable',
            'billing_zip' => $this->billingDifferent ? 'required|string|max:20' : 'nullable',
            'billing_country' => $this->billingDifferent ? 'required|string|max:100' : 'nullable',
        ];

        if ($this->askForAccount && !$this->loggedInCustomer) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function mount()
    {
        session()->forget('checkout_customer_info');

        if (auth('customer')->check()) {
            $customer = auth('customer')->user();
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->phone = $customer->phone ?? '';
            $this->address_line1 = $customer->address_line1 ?? '';
            $this->address_line2 = $customer->address_line2 ?? '';
            $this->city = $customer->city ?? '';
            $this->state = $customer->state ?? '';
            $this->zip = $customer->zip ?? '';
            $this->country = $customer->country ?? '';
            $this->loggedInCustomer = true;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $this->validate();

        $this->showLoginButton = false;

        if (!$this->billingDifferent) {
            $this->billing_address_line1 = $this->address_line1;
            $this->billing_address_line2 = $this->address_line2;
            $this->billing_city = $this->city;
            $this->billing_state = $this->state;
            $this->billing_zip = $this->zip;
            $this->billing_country = $this->country;
        }

        if ($this->askForAccount && !$this->loggedInCustomer) {

            $existingCustomer = Customer::where('email', $this->email)->first();

            if ($existingCustomer) {
                $this->addError('email', 'An account with this email already exists. Please log in!');
                $this->showLoginButton = true;
                return;
            }

            $customer = Customer::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'country' => $this->country,
                'password' => Hash::make($this->password),
            ]);

            auth('customer')->login($customer);

            $this->loggedInCustomer = true;
        } else {
            $existingCustomer = Customer::where('email', $this->email)->first();

            if ($existingCustomer && !$this->loggedInCustomer) {
                $this->addError('email', 'An account with this email already exists. Please log in or reset your password!');
                $this->showLoginButton = true;
                return;
            }
        }

        $customerData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
        ];

        if ($this->billingDifferent) {
            $customerData = array_merge($customerData, [
                'billing_different' => true,
                'billing_address_line1' => $this->billing_address_line1,
                'billing_address_line2' => $this->billing_address_line2,
                'billing_city' => $this->billing_city,
                'billing_state' => $this->billing_state,
                'billing_zip' => $this->billing_zip,
                'billing_country' => $this->billing_country,
            ]);
        } else {
            $customerData = array_merge($customerData, [
                'billing_different' => false,
            ]);
        }

        session()->put('checkout_customer_info', $customerData);

        return redirect()->route('shop.checkout-shipping');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-form');
    }
}
