<?php

namespace App\Livewire\Tenant\Backend\Statistics;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class ShopStatistics extends Component
{
    public $monthlyRevenue = [];
    public $productRevenue = [];
    public $customerRevenue = [];
    public $bestSelling = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    protected function loadStatistics()
    {
        $this->monthlyRevenue = Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue")
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'label' => Carbon::createFromFormat('Y-m', $row->month)->format('M Y'),
                'revenue' => (float) $row->revenue,
            ]);

        $this->productRevenue = OrderItem::selectRaw('product_id, SUM(price * quantity) as revenue, SUM(quantity) as total_sold')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->with('product:id,name')
            ->get()
            ->map(fn ($item) => [
                'product' => $item->product->name ?? 'N/A',
                'revenue' => (float) $item->revenue,
                'total_sold' => $item->total_sold,
            ]);

        $this->customerRevenue = Order::selectRaw('customer_id, SUM(total_amount) as total_spent, COUNT(*) as orders_count')
            ->where('status', 'completed')
            ->groupBy('customer_id')
            ->orderByDesc('total_spent')
            ->with('customer:id,name')
            ->get()
            ->map(fn ($row) => [
                'customer' => $row->customer->name ?? 'Unknown',
                'total_spent' => (float) $row->total_spent,
                'orders_count' => $row->orders_count,
            ]);

        $this->bestSelling = $this->productRevenue->sortByDesc('total_sold')->take(5)->values();
    }

    public function render()
    {
        return view('livewire.tenant.backend.statistics.shop-statistics');
    }
}
