<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerOrderView extends Component
{
    public $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-order-view');
    }
}
