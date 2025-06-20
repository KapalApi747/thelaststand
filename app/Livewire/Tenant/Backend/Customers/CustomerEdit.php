<?php

namespace App\Livewire\Tenant\Backend\Customers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class CustomerEdit extends Component
{
    public Customer $customer;
    public CustomerAddress $shippingAddress;
    public CustomerAddress $billingAddress;

    public $name, $email, $phone;

    public $is_active;

    public $shipping = [];
    public $billing = [];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;

        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->is_active = $customer->is_active ? '1' : '0';

        $this->shippingAddress = $customer->addresses->firstWhere('type', 'shipping') ?? new CustomerAddress(['type' => 'shipping']);
        $this->billingAddress = $customer->addresses->firstWhere('type', 'billing') ?? new CustomerAddress(['type' => 'billing']);

        $this->shipping = $this->shippingAddress->only(['address_line1', 'address_line2', 'city', 'state', 'zip', 'country']);
        $this->billing = $this->billingAddress->only(['address_line1', 'address_line2', 'city', 'state', 'zip', 'country']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $this->customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => (bool) $this->is_active,
        ]);

        foreach (['shipping' => $this->shippingAddress, 'billing' => $this->billingAddress] as $type => $model) {
            $data = $this->{$type};
            $model->fill($data);
            $model->type = $type;
            $model->customer_id = $this->customer->id;
            $model->save();
        }

        session()->flash('message', 'Customer updated successfully!');
    }

    public function render()
    {
        return view('livewire.tenant.backend.customers.customer-edit');
    }
}
