<?php

namespace App\Livewire\Tenant\Backend\Orders;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class OrderView extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load([
            'customer',
            'items.product',
            'payments',
            'addresses',
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.backend.orders.order-view');
    }
}
