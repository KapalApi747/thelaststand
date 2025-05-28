<?php

namespace App\Livewire\Tenant\Frontend\Main;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[layout('t-shop-layout')]
class ShopProducts extends Component
{
    public $products;
    public $selectedProduct;

    public function mount()
    {
        $this->products = Product::with(['images', 'categories', 'variants'])->where('is_active', 1)->get();
    }

    public function showProductModal($productId)
    {
        $this->selectedProduct = Product::with(['images', 'categories', 'variants'])->findOrFail($productId);
        $this->modal('product-details')->show();
    }

    public function closeModal()
    {
        $this->modal('product-details')->close();
    }

    public function render()
    {
        return view('livewire.tenant.frontend.main.shop-products');
    }
}
