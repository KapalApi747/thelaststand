<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerVerify extends Component
{
    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-verify');
    }
}
