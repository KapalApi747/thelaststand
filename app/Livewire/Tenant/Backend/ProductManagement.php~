<?php

namespace App\Livewire\Tenant\Backend;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class ProductManagement extends Component
{
    public function render()
    {
        return view('livewire.tenant.backend.product-management', [
            'products' => Product::on('mysql')->whereHas('tenants', fn($q) => $q->where('tenants.id', tenant()->id))->latest()->get()
        ]);
    }
}
