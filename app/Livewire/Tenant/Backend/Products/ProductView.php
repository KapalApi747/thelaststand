<?php

namespace App\Livewire\Tenant\Backend\Products;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class ProductView extends Component
{
    public $product;

    public function mount(Product $product)
    {
        $product->load('images', 'categories');

        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.tenant.backend.products.product-view');
    }
}
