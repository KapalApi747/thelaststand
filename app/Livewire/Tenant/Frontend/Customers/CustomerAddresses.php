<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Layout('t-shop-layout')]
class CustomerAddresses extends Component
{
    public $addresses = [];
    public $editingAddress = [
        'id' => null,
        'type' => '',
        'address_line1' => '',
        'address_line2' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'country' => '',
    ];
    public $isEditing = false;

    protected function rules()
    {
        return [
            'editingAddress.type' => 'required|in:shipping,billing',
            'editingAddress.address_line1' => 'required|string|max:255',
            'editingAddress.address_line2' => 'nullable|string|max:255',
            'editingAddress.city' => 'required|string|max:100',
            'editingAddress.state' => 'nullable|string|max:100',
            'editingAddress.zip' => 'nullable|string|max:20',
            'editingAddress.country' => 'required|string|max:100',
        ];
    }

    public function mount()
    {
        $this->loadAddresses();
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

    public function loadAddresses()
    {
        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $this->addresses = $customer->addresses()->get()->toArray();
    }

    public function addAddress()
    {
        $this->resetEditingAddress();
        $this->isEditing = true;
    }

    public function editAddress($id)
    {
        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $address = $customer->addresses()->findOrFail($id);
        $this->editingAddress = $address->toArray();
        $this->isEditing = true;
    }

    public function saveAddress()
    {
        $this->validate();

        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        if ($this->editingAddress['id']) {
            $address = $customer->addresses()->findOrFail($this->editingAddress['id']);
            $address->update($this->editingAddress);
        } else {
            $customer->addresses()->create($this->editingAddress);
        }

        $this->isEditing = false;
        $this->loadAddresses();

        session()->flash('message', 'Address saved successfully.');
    }

    public function deleteAddress($id)
    {
        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $address = $customer->addresses()->findOrFail($id);
        $address->delete();

        $this->loadAddresses();
        session()->flash('message', 'Address deleted successfully.');
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->resetEditingAddress();
    }

    protected function resetEditingAddress()
    {
        $this->editingAddress = [
            'id' => null,
            'type' => 'shipping',
            'address_line1' => '',
            'address_line2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
        ];
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-addresses');
    }
}
