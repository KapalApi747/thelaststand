<?php

namespace App\Livewire\Tenant\Backend\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class CustomerView extends Component
{
    public Customer $customer;

    public function mount($customerId)
    {
        $this->customer = Customer::with([
            'orders',
            'addresses',
            'reviews',
        ])->findOrFail($customerId);
    }

    public function render()
    {
        return view('livewire.tenant.backend.customers.customer-view');
    }
}
