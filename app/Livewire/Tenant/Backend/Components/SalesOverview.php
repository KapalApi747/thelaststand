<?php

namespace App\Livewire\Tenant\Backend\Components;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SalesOverview extends Component
{
    public $salesChartData = [];
    public $salesChartLabels = [];

    public $salesThisMonth;
    public $salesGrowth;
    public $avgRevenue;

    public function mount()
    {
        $this->generateChartData();
        $this->generateSalesMetrics();
    }

    protected function generateChartData()
    {
        $revenues = DB::table('orders')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(total_amount) as revenue")
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = now()->subMonths($i)->format('Y-m');
            $months->push($monthKey);
        }

        $this->salesChartLabels = $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->format('M Y'))->toArray();
        $this->salesChartData = $months->map(fn($m) => (float) ($revenues[$m]->revenue ?? 0))->toArray();
    }

    protected function generateSalesMetrics()
    {
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $currentSales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $lastSales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $this->salesThisMonth = $currentSales;

        $this->salesGrowth = $lastSales > 0
            ? round((($currentSales - $lastSales) / $lastSales) * 100, 1)
            : null;

        $currentRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total_amount');

        $lastRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_amount');

        $currentAvg = $currentSales > 0 ? $currentRevenue / $currentSales : 0;
        $lastAvg = $lastSales > 0 ? $lastRevenue / $lastSales : 0;

        $this->avgRevenue = $lastAvg > 0
            ? round((($lastAvg - $currentAvg) / $lastAvg) * 100, 1)
            : null;
    }

    public function render()
    {
        return view('livewire.tenant.backend.components.sales-overview');
    }
}
