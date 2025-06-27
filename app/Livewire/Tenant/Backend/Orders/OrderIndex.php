<?php

namespace App\Livewire\Tenant\Backend\Orders;

use App\Models\Order;
use App\Services\CsvExportService;
use Barryvdh\DomPDF\Facade\Pdf;
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
    public $minAmount = null;

    public bool $selectAllOrders = false;
    public bool $selectAllOnPage = false;
    public bool $selectAllFiltered = false;

    public array $selectedOrders = [];
    public string $bulkAction = '';
    public string $newStatus = '';

    public function mount()
    {
        $this->orderCount = Order::count();
        $this->totalRevenue = Order::whereIn('status', ['completed', 'paid', 'delivered', 'shipped'])->sum('total_amount');
    }

    public function allStatuses(string $status): string
    {
        return match ($status) {
            'pending'    => 'bg-yellow-100 text-yellow-800 border border-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800 border border-blue-800',
            'shipped'    => 'bg-indigo-100 text-indigo-800 border border-indigo-800',
            'delivered'  => 'bg-emerald-100 text-emerald-800 border border-emerald-800',
            'completed'  => 'bg-green-100 text-green-800 border border-green-800',
            'paid'       => 'bg-teal-100 text-teal-800 border border-teal-800',
            'cancelled'  => 'bg-gray-200 text-gray-700 border border-gray-700',
            'refunded'   => 'bg-pink-100 text-pink-800 border border-pink-800',
            'failed'     => 'bg-red-100 text-red-800 border border-red-800',
            default      => 'bg-gray-100 text-gray-800 border border-gray-800',
        };
    }

    public function updateBulkStatus()
    {
        if (empty($this->selectedOrders) || empty($this->newStatus)) {
            session()->flash('message', 'Please select orders and a new status.');
            return;
        }

        Order::whereIn('id', $this->selectedOrders)
            ->update(['status' => $this->newStatus]);

        session()->flash('message', 'Order statuses updated successfully.');

        $this->reset(['selectedOrders', 'selectAllOnPage', 'selectAllFiltered', 'selectAllOrders', 'bulkAction', 'newStatus']);
    }

    public function applyBulkAction()
    {
        if (empty($this->selectedOrders)) return;

        switch ($this->bulkAction) {
            case 'export':
                return $this->exportCsv(app(CsvExportService::class));
            case 'print_invoices':
                $orders = Order::whereIn('id', $this->selectedOrders)->get();

                $pdf = Pdf::loadView('exports.orders.bulk-invoices', compact('orders'))
                    ->setPaper('a4', 'portrait');

                return response()->streamDownload(
                    fn () => print($pdf->output()),
                    'bulk-invoices.pdf'
                );
        }
    }

    public function updatedSelectedOrders()
    {
        $this->selectAllOrders = false;
    }

    public function updatedSelectAllOrders($value)
    {
        if ($value) {
            $query = Order::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"));

            $this->selectedOrders = $query->pluck('id')->toArray();

            $this->selectAllOnPage = false;
            $this->selectAllFiltered = false;
        } else {
            $this->selectedOrders = [];
        }
    }

    public function updatedSelectAllOnPage($value)
    {
        if ($value) {
            $page = $this->getPage();

            $query = Order::query()
                ->select('orders.*')
                ->when($this->search, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('orders.order_number', 'like', '%' . $this->search . '%')
                            ->orWhereHas('customer', function ($q) {
                                $q->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('email', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->when($this->status !== '', function ($q) {
                    $q->where('status', $this->status);
                })
                ->when($this->minAmount!== null, function ($q) {
                    $q->where('total_amount', '>=', $this->minAmount);
                })
                ->orderBy($this->sortField, $this->sortDirection);

            $paginated = $query->paginate($this->pagination, ['*'], 'page', $page);

            $this->selectedOrders = $paginated->pluck('id')->toArray();

            $this->selectAllFiltered = false;
            $this->selectAllOrders = false;
        } else {
            $this->selectedOrders = [];
        }
    }

    public function updatedSelectAllFiltered($value)
    {
        if ($value) {
            $query = Order::query()
                ->select('orders.*')
                ->when($this->search, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('orders.order_number', 'like', '%' . $this->search . '%')
                            ->orWhereHas('customer', function ($q) {
                                $q->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('email', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->when($this->status !== '', function ($q) {
                    $q->where('status', $this->status);
                })
                ->when($this->minAmount !== null, function ($q) {
                    $q->where('total_amount', '>=', $this->minAmount);
                });

            $this->selectedOrders = $query->pluck('id')->toArray();

            $this->selectAllOnPage = false;
            $this->selectAllOrders = false;
        } else {
            $this->selectedOrders = [];
        }
    }

    public function exportCsv(CsvExportService $csv)
    {
        $orders = Order::with('customer')
            ->whereIn('id', $this->selectedOrders)
            ->get();

        return $csv->export(
            ['Order ID', 'Customer', 'Status', 'Total (€)', 'Date'],
            $orders,
            fn ($order) => [
                $order->order_number,
                optional($order->customer)->name ?? 'N/A',
                ucfirst($order->status),
                number_format($order->total_amount, 2),
                $order->created_at->format('Y-m-d'),
            ],
            'orders.csv'
        );
    }

    public function updatingPagination()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetSelection();
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetSelection();
        $this->resetPage();
    }

    public function updatedMinAmount()
    {
        $this->resetSelection();
        $this->resetPage();
    }

    public function updatedPagination()
    {
        $this->resetSelection();
        $this->resetPage();
    }

    public function resetSelection()
    {
        $this->selectAllFiltered = false;
        $this->selectAllOnPage = false;
        $this->selectedOrders = [];
        $this->selectAllOrders = false;
        $this->bulkAction = '';
        $this->newStatus = '';
    }

    public function render()
    {
        $failedCount = Order::where('status', 'failed')->count();
        $pendingCount = Order::where('status', 'pending')->count();

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
            ->when($this->minAmount !== null, function ($q) {
                $q->where('total_amount', '>=', $this->minAmount);
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
            'failedCount' => $failedCount,
            'pendingCount' => $pendingCount,
        ]);
    }
}
