<?php

namespace App\Livewire\Tenant\Backend\Customers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class CustomerCreation extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public bool $is_active = true;

    public string $password = '';
    public string $password_confirmation = '';

    public array $shipping = [
        'address_line1' => '',
        'address_line2' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'country' => '',
    ];

    public array $billing = [
        'address_line1' => '',
        'address_line2' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'country' => '',
    ];

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customers', 'email')],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
            'password' => ['required', 'min:8', 'confirmed'],

            'shipping.address_line1' => ['required', 'string', 'max:255'],
            'shipping.city' => ['required', 'string', 'max:100'],
            'shipping.zip' => ['required', 'string', 'max:20'],
            'shipping.country' => ['required', 'string', 'max:100'],

            'billing.address_line1' => ['nullable', 'string', 'max:255'],
            'billing.city' => ['nullable', 'string', 'max:100'],
            'billing.zip' => ['nullable', 'string', 'max:20'],
            'billing.country' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function save()
    {
        $this->validate();

        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'password' => Hash::make($this->password),
        ]);

        CustomerAddress::create(array_merge($this->shipping, [
            'customer_id' => $customer->id,
            'type' => 'shipping',
        ]));

        if (!empty($this->billing['address_line1'])) {
            CustomerAddress::create(array_merge($this->billing, [
                'customer_id' => $customer->id,
                'type' => 'billing',
            ]));
        }

        session()->flash('message', 'Customer created successfully!');

        $this->reset();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.tenant.backend.customers.customer-creation');
    }
}
