<?php

namespace App\Livewire\Tenant\Backend\Orders;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('t-dashboard-layout')]
class OrderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $pagination = 20;
    public $sortField = 'order_number';
    public $sortDirection = 'asc';

    public $orderCount;
    public $totalRevenue;

    public function mount()
    {
        $this->orderCount = Order::count();
        $this->totalRevenue = Order::where('status', 'completed')->sum('total_amount');
    }

    public function getStatus(string $status): string
    {
        return match ($status) {
            'pending'    => 'bg-yellow-100 text-yellow-800 border border-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800 border border-blue-800',
            'shipped'    => 'bg-indigo-100 text-indigo-800 border border-indigo-800',
            'delivered'  => 'bg-emerald-100 text-emerald-800 border border-emerald-800',
            'completed'  => 'bg-green-100 text-green-800 border border-green-800',
            'cancelled'  => 'bg-gray-200 text-gray-700 border border-gray-700',
            'refunded'   => 'bg-pink-100 text-pink-800 border border-pink-800',
            'failed'     => 'bg-red-100 text-red-800 border border-red-800',
            default      => 'bg-gray-100 text-gray-800 border border-gray-800',
        };
    }


    public function render()
    {
        $orders = Order::query()
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*')
            ->with(['customer', 'items.product', 'payments', 'addresses'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('customers.name', 'like', '%' . $this->search . '%')
                        ->orWhere('orders.order_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('orders.status', $this->status);
            })
            ->orderBy(
                $this->sortField === 'customer.name' ? 'customers.name' : $this->sortField,
                $this->sortDirection
            )
            ->paginate($this->pagination);

        return view('livewire.tenant.backend.orders.order-index', [
            'orders' => $orders,
            'orderCount' => $this->orderCount,
            'totalRevenue' => $this->totalRevenue,
        ]);
    }
}
