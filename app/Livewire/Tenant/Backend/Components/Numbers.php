<?php

namespace App\Livewire\Tenant\Backend\Components;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use Livewire\Component;

class Numbers extends Component
{
    public $totalSales;
    public $totalPayments;
    public $totalOrders;
    public $totalItems;
    public $totalReviews;
    public $totalComments;
    public $totalCustomers;
    public $totalInactiveCustomers;

    public function mount()
    {
        $this->totalSales = OrderItem::whereHas('order', function ($query) {
            $query->whereIn('status', ['completed', 'paid', 'shipped', 'delivered']);
        })->sum('quantity');
        $this->totalPayments = Payment::whereIn('status', ['completed', 'paid'])->count();

        $this->totalOrders = Order::count();
        $this->totalItems = OrderItem::sum('quantity');

        $this->totalReviews = ProductReview::count();
        $this->totalComments = ProductReviewReply::count();

        $this->totalCustomers = Customer::count();
        $this->totalInactiveCustomers = Customer::where('is_active', 0)->count();
    }
    public function render()
    {
        return view('livewire.tenant.backend.components.numbers');
    }
}
