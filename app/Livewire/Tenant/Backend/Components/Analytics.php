<?php

namespace App\Livewire\Tenant\Backend\Components;

use App\Models\Customer;
use App\Models\Order;
use Livewire\Component;

class Analytics extends Component
{
    public $orderChartData = [];
    public $customerChartData = [];
    public $totalOrders;
    public $totalCustomers;

    public function mount()
    {
        $this->orderChartData = $this->orderChartData();
        $this->customerChartData = $this->newCustomerMonthlyChartData();
        $this->totalOrders = Order::count();
        $this->totalCustomers = Customer::count();
    }

    public function orderChartData()
    {
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        // Fetch counts grouped by date in a single query
        $ordersByDate = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $dates = collect();
        $values = collect();

        // Fill all 30 days with counts (0 if no orders)
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates->push($date);
            $values->push($ordersByDate->get($date, 0));
        }

        return [
            'labels' => $dates,
            'data' => $values,
        ];
    }

    public function newCustomerMonthlyChartData()
    {
        $startDate = now()->startOfMonth()->subMonths(6);
        $endDate = now()->startOfMonth()->subDay();

        $customersByMonth = Customer::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $months = collect();
        $values = collect();

        for ($i = 6; $i > 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i)->format('Y-m');
            $months->push($month);
            $values->push($customersByMonth->get($month, 0));
        }

        return [
            'labels' => $months,
            'data' => $values,
        ];
    }

    public function render()
    {
        return view('livewire.tenant.backend.components.analytics');
    }
}
