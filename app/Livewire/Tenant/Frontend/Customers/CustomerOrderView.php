<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerOrderView extends Component
{
    public $order;

    public function mount(Order $order)
    {
        $customerId = $this->currentCustomer()->id;

        if ($order->customer_id !== $customerId) {
            abort(403, 'Unauthorized access to this order.');
        }

        $this->order = $order;
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

    public function exportPDF()
    {
        $customer = $this->currentCustomer();

        $order = Order::with(['items', 'addresses', 'payments', 'shipments'])
            ->where('customer_id', $customer->id)
            ->findOrFail($this->order->id);

        $pdf = Pdf::loadView('exports.orders.order-invoice', compact('order'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice-' . $order->order_number . '.pdf');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-order-view');
    }
}
