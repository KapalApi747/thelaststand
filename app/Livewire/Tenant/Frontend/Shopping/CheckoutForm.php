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
    public $shipping_address_id = null; // selected shipping address
    public $billing_address_id = null; // selected billing address (optional)
    public $useNewShippingAddress = false;
    public $useNewBillingAddress = false;

// For the address form fields (shipping + billing)
    public $addressFields = [
        'shipping' => [
            'phone' => '',
            'address_line1' => '',
            'address_line2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
        ],
        'billing' => [
            'address_line1' => '',
            'address_line2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
        ],
    ];


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

        $customer = $this->currentCustomer();

        if ($customer) {
            $customer = $this->currentCustomer();
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->phone = $customer->phone ?? '';
            $this->address_line1 = $customer->addresses->firstWhere('type', 'shipping')->address_line1 ?? '';
            $this->address_line2 = $customer->addresses->firstWhere('type', 'shipping')->address_line2 ?? '';
            $this->city = $customer->addresses->firstWhere('type', 'shipping')->city ?? '';
            $this->state = $customer->addresses->firstWhere('type', 'shipping')->state ?? '';
            $this->zip = $customer->addresses->firstWhere('type', 'shipping')->zip ?? '';
            $this->country = $customer->addresses->firstWhere('type', 'shipping')->country ?? '';
            $this->loggedInCustomer = true;
        }
    }

    protected function currentCustomer()
    {
        if ($customer = auth('customer')->user()) {
            return $customer;
        }

        if ($tenantUser = auth('web')->user()) {
            return $tenantUser->customers()->first();
        }

        return null;
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

        $existingCustomer = Customer::where('email', $this->email)->first();

        if ($this->askForAccount && !$this->loggedInCustomer) {
            if ($existingCustomer) {
                if ($existingCustomer->password === null) {
                    // Upgrade guest account to full account
                    $existingCustomer->update([
                        'name' => $this->name,
                        'phone' => $this->phone,
                        'address_line1' => $this->address_line1,
                        'address_line2' => $this->address_line2,
                        'city' => $this->city,
                        'state' => $this->state,
                        'zip' => $this->zip,
                        'country' => $this->country,
                        'password' => Hash::make($this->password),
                    ]);

                    auth('customer')->login($existingCustomer);
                    $this->loggedInCustomer = true;
                } else {
                    $this->addError('email', 'An account with this email already exists. Please log in!');
                    $this->showLoginButton = true;
                    return;
                }
            } else {
                // No existing customer — create a new one
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
            }
        } else {
            // Not asking to create account
            if ($existingCustomer && !$this->loggedInCustomer) {
                if ($existingCustomer->password !== null) {
                    $this->addError('email', 'An account with this email already exists. Please log in or reset your password!');
                    $this->showLoginButton = true;
                    return;
                }
                // else: it's a guest, allow flow to continue
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
            $customerData['billing_different'] = false;
        }

        session()->put('checkout_customer_info', $customerData);

        return redirect()->route('shop.checkout-shipping');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-form');
    }
}
