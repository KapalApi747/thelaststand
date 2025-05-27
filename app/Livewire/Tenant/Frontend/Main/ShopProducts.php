<?php

namespace App\Livewire\Tenant\Frontend\Main;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[layout('t-shop-layout')]
class ShopProducts extends Component
{
    public $products;

    public function mount()
    {
        $this->products = Product::with('images')->where('is_active', 1)->get();
    }

    public function render()
    {
        return view('livewire.tenant.frontend.main.shop-products');
    }
}
