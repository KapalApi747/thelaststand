<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerOrders extends Component
{
    public $orders;

    public function mount()
    {
        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $this->orders = Order::with(['items', 'shipments', 'payments'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();
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

    public function exportPDF($orderId)
    {
        $customer = $this->currentCustomer();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $order = Order::with(['items', 'addresses', 'payments', 'shipments'])
            ->where('customer_id', $customer->id)
            ->findOrFail($orderId);

        $pdf = Pdf::loadView('exports.orders.order-invoice', compact('order'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice-' . $order->order_number . '.pdf');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-orders');
    }
}
