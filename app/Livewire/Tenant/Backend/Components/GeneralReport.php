<?php

namespace App\Livewire\Tenant\Backend\Components;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;

class GeneralReport extends Component
{
    public Order $order;

    // Items sold
    public $itemsSold, $itemsSoldChange, $itemsSoldChevron;

    // New orders
    public $newOrders, $newOrdersChange, $newOrdersChevron;

    // Total products
    public $newProducts, $newProductsChange, $newProductsChevron, $currentProducts, $previousProducts;

    // New customers
    public $newCustomers, $newCustomersChange, $newCustomersChevron;

    public function mount()
    {
        $this->loadItemsSoldReport();
        $this->loadNewOrdersReport();
        $this->loadTotalProductsReport();
        $this->loadNewCustomersReport();
    }

    protected function calculateChange($current, $previous)
    {
        if ($previous == 0 && $current == 0) {
            return 0;
        } elseif ($previous == 0) {
            return 100;
        }

        return round((($current - $previous) / $previous) * 100);
    }

    public function loadItemsSoldReport()
    {
        $startCurrent = now()->copy()->startOfMonth();
        $endCurrent = now()->copy()->endOfMonth();

        $startPrevious = now()->copy()->subMonth()->startOfMonth();
        $endPrevious = now()->copy()->subMonth()->endOfMonth();

        $currentCount = OrderItem::whereHas('order', function ($query) use ($startCurrent, $endCurrent) {
            $query->whereIn('status', ['completed', 'paid', 'shipped', 'delivered'])
                ->whereBetween('created_at', [$startCurrent, $endCurrent]);
        })->sum('quantity');

        $previousCount = OrderItem::whereHas('order', function ($query) use ($startPrevious, $endPrevious) {
            $query->whereIn('status', ['completed', 'paid', 'shipped', 'delivered'])
                ->whereBetween('created_at', [$startPrevious, $endPrevious]);
        })->sum('quantity');

        $this->itemsSold = $currentCount;
        $this->itemsSoldChange = $this->calculateChange($currentCount, $previousCount);
        $this->itemsSoldChevron = $this->itemsSoldChange >= 0 ? 'up' : 'down';
    }

    public function loadNewOrdersReport()
    {
        $startCurrent = now()->copy()->startOfMonth();
        $endCurrent = now()->copy()->endOfMonth();

        $startPrevious = now()->copy()->subMonth()->startOfMonth();
        $endPrevious = now()->copy()->subMonth()->endOfMonth();

        $currentCount = Order::whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $previousCount = Order::whereBetween('created_at', [$startPrevious, $endPrevious])->count();

        $this->newOrders = $currentCount;
        $this->newOrdersChange = $this->calculateChange($currentCount, $previousCount);
        $this->newOrdersChevron = $this->newOrdersChange >= 0 ? 'up' : 'down';
    }

    public function loadTotalProductsReport()
    {
        $startCurrent = now()->copy()->startOfMonth();
        $endCurrent = now()->copy()->endOfMonth();

        $startPrevious = now()->copy()->subMonth()->startOfMonth();
        $endPrevious = now()->copy()->subMonth()->endOfMonth();

        $currentCount = Product::whereBetween('created_at', [$startCurrent, $endCurrent])->count()
            + ProductVariant::whereBetween('created_at', [$startCurrent, $endCurrent])->count();

        $previousCount = Product::whereBetween('created_at', [$startPrevious, $endPrevious])->count()
            + ProductVariant::whereBetween('created_at', [$startPrevious, $endPrevious])->count();

        $this->currentProducts = $currentCount;
        $this->previousProducts = $previousCount;
        $this->newProducts = Product::count() + ProductVariant::count();
        $this->newProductsChange = $this->calculateChange($currentCount, $previousCount);
        $this->newProductsChevron = $this->newProductsChange >= 0 ? 'up' : 'down';
    }

    public function loadNewCustomersReport()
    {
        $startCurrent = now()->copy()->startOfMonth();
        $endCurrent = now()->copy()->endOfMonth();

        $startPrevious = now()->copy()->subMonth()->startOfMonth();
        $endPrevious = now()->copy()->subMonth()->endOfMonth();

        $currentCount = Customer::whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $previousCount = Customer::whereBetween('created_at', [$startPrevious, $endPrevious])->count();

        $this->newCustomers = $currentCount;
        $this->newCustomersChange = $this->calculateChange($currentCount, $previousCount);
        $this->newCustomersChevron = $this->newCustomersChange >= 0 ? 'up' : 'down';
    }

    public function render()
    {
        return view('livewire.tenant.backend.components.general-report');
    }
}
