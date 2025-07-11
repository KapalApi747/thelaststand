<?php

namespace App\Livewire\Tenant\Backend\Components;

use App\Models\Product;
use Livewire\Component;

class QuickInfo extends Component
{
    public $popularProducts;

    public function mount()
    {
        $this->popularProducts = Product::select('products.id', 'products.name', 'products.slug', 'products.price')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.tenant.backend.components.quick-info');
    }
}
