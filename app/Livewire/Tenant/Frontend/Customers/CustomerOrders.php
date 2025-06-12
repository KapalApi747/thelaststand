<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerOrders extends Component
{
    public $orders;

    public function mount()
    {
        $this->orders = Order::with(['items', 'shipment', 'payments'])
            ->where('customer_id', auth('customer')->user()->id)
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-orders');
    }
}
