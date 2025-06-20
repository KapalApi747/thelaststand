<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerProfile extends Component
{
    public $name;
    public $email;
    public $phone;

    public $paymentAccounts = [];

    public function mount()
    {
        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;

        $this->paymentAccounts = $customer->paymentAccounts()->get()->toArray();
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

    protected function rules()
    {
        $customer = $this->currentCustomer();

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => 'nullable|string|max:30',
        ];
    }

    public function save()
    {
        $this->validate();

        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('message', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-profile');
    }
}
