<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

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

    protected function rules()
    {
        return [
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

        if (!$this->billingDifferent) {
            $this->billing_address_line1 = $this->address_line1;
            $this->billing_address_line2 = $this->address_line2;
            $this->billing_city = $this->city;
            $this->billing_state = $this->state;
            $this->billing_zip = $this->zip;
            $this->billing_country = $this->country;
        }

        // Save to session for use in Stripe checkout & order saving
        session()->put('checkout_customer_info', [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
        ]);

        // Redirect to the payment page (where StripePaymentButton is)
        return redirect()->route('shop.checkout-payment');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-form');
    }
}
