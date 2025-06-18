<?php

namespace App\Livewire\Tenant\Backend\Customers;

use App\Models\Customer;
use App\Services\CsvExportService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('t-dashboard-layout')]
class CustomerIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $is_active = '';

    public $pagination = 20;
    public int $currentPage = 1;

    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $minTotalSpent = null;
    public $customerCount;

    public bool $selectAllCustomers = false;
    public bool $selectAllOnPage = false;
    public bool $selectAllFiltered = false;

    public array $selectedCustomers = [];
    public string $bulkAction = '';
    public $newStatus = '';

    public function mount()
    {
        $this->customerCount = Customer::count();
    }

    public function updateBulkStatus()
    {
        if (empty($this->selectedCustomers) || $this->newStatus === '') {
            session()->flash('message', 'Please select customers and a new status.');
            return;
        }

        Customer::whereIn('id', $this->selectedCustomers)
            ->update(['is_active' => $this->newStatus === 'true']);

        session()->flash('message', 'Customer statuses updated successfully.');

        $this->reset(['selectedCustomers', 'selectAllCustomers', 'selectAllOnPage', 'selectAllFiltered', 'bulkAction', 'newStatus']);
    }

    public function applyBulkAction()
    {
        if (empty($this->selectedCustomers)) {
            session()->flash('message', 'Please select at least one customer.');
            return;
        }

        if ($this->bulkAction === 'export') {
            return $this->exportCsv(app(CsvExportService::class));
        }

        session()->flash('message', 'Please select a valid bulk action.');
    }

    public function updatedSelectAllCustomers($value)
    {
        if ($value) {
            $query = Customer::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"));

            $this->selectedCustomers = $query->pluck('id')->toArray();

            $this->selectAllOnPage = false;
            $this->selectAllFiltered = false;
        } else {
            $this->selectedCustomers = [];
        }
    }

    public function updatedSelectAllOnPage($value)
    {
        if ($value) {
            $page = $this->getPage();

            $query = Customer::query()
                ->select('customers.*')
                ->withSum('orders', 'total_amount')
                ->when($this->search, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('customers.name', 'like', '%' . $this->search . '%')
                            ->orWhere('customers.email', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->is_active !== '', function ($q) {
                    $q->where('is_active', $this->is_active === 'true' ? 1 : 0);
                })
                ->when($this->minTotalSpent !== null, function ($q) {
                    $q->havingRaw('orders_sum_total_amount >= ?', [$this->minTotalSpent]);
                })
                ->orderBy(
                    $this->sortField === 'total_spent' ? 'orders_sum_total_amount' : $this->sortField,
                    $this->sortDirection
                );

            $paginated = $query->paginate($this->pagination, ['*'], 'page', $page);

            $this->selectedCustomers = $paginated->pluck('id')->toArray();

            $this->selectAllFiltered = false;
            $this->selectAllCustomers = false;
        } else {
            $this->selectedCustomers = [];
        }
    }

    public function updatedSelectAllFiltered($value)
    {
        if ($value) {
            $query = Customer::query()
                ->select('customers.*')
                ->withSum('orders', 'total_amount')
                ->when($this->search, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('customers.name', 'like', '%' . $this->search . '%')
                            ->orWhere('customers.email', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->is_active !== '', function ($q) {
                    $q->where('is_active', $this->is_active === 'true' ? 1 : 0);
                })
                ->when($this->minTotalSpent !== null, function ($q) {
                    $q->havingRaw('orders_sum_total_amount >= ?', [$this->minTotalSpent]);
                });

            $this->selectedCustomers = $query->pluck('id')->toArray();

            // Uncheck the other selection toggles
            $this->selectAllOnPage = false;
            $this->selectAllCustomers = false;
        } else {
            $this->selectedCustomers = [];
        }
    }

    public function updatedSelectedCustomers()
    {
        $this->selectAllCustomers = false;
    }

    public function exportCsv(CsvExportService $csv)
    {
        $customers = Customer::with(['orders', 'addresses'])
            ->whereIn('id', $this->selectedCustomers)
            ->get();

        $headers = [
            'Customer ID', 'Name', 'Email', 'Phone', 'Status', 'Joined At',
            'Last Order Date', 'Total Orders', 'Total Spent',

            'Shipping Address Line 1', 'Shipping Address Line 2', 'Shipping City',
            'Shipping State', 'Shipping Zip', 'Shipping Country',

            'Billing Address Line 1', 'Billing Address Line 2', 'Billing City',
            'Billing State', 'Billing Zip', 'Billing Country',
        ];

        return $csv->export(
            $headers,
            $customers,
            function ($customer) {
                $shipping = $customer->addresses->firstWhere('type', 'shipping');
                $billing = $customer->addresses->firstWhere('type', 'billing');
                $orders = $customer->orders;

                return [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->is_active ? 'Active' : 'Inactive',
                    optional($customer->created_at)->format('Y-m-d'),

                    optional($orders->sortByDesc('created_at')->first()?->created_at)->format('Y-m-d'),
                    $orders->count(),
                    number_format($orders->sum('total_amount'), 2),

                    optional($shipping)->address_line1,
                    optional($shipping)->address_line2,
                    optional($shipping)->city,
                    optional($shipping)->state,
                    optional($shipping)->zip,
                    optional($shipping)->country,

                    optional($billing)->address_line1,
                    optional($billing)->address_line2,
                    optional($billing)->city,
                    optional($billing)->state,
                    optional($billing)->zip,
                    optional($billing)->country,
                ];
            },
            'customers.csv'
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

    public function updatedIsActive()
    {
        $this->resetSelection();
        $this->resetPage();
    }

    public function updatedMinTotalSpent()
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
        $this->selectedCustomers = [];
        $this->selectAllCustomers = false;
        $this->bulkAction = '';
        $this->newStatus = '';
    }

    public function render()
    {
        $customers = Customer::query()
            ->select('customers.*')
            ->withSum('orders', 'total_amount')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('customers.name', 'like', '%' . $this->search . '%')
                        ->orWhere('customers.email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->is_active !== '', function ($query) {
                $value = $this->is_active === 'true' ? 1 : 0;
                $query->where('is_active', $value);
            })
            ->when($this->minTotalSpent !== null, function ($query) {
                $query->havingRaw('orders_sum_total_amount >= ?', [$this->minTotalSpent]);
            })
            ->when($this->sortField === 'total_spent', function ($query) {
                $query->orderBy('orders_sum_total_amount', $this->sortDirection);
            }, function($query) {
                $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate($this->pagination);

        return view('livewire.tenant.backend.customers.customer-index', [
            'customers' => $customers,
        ]);
    }
}
