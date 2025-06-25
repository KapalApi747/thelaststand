<?php

namespace App\Livewire\Tenant\Backend\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('t-dashboard-layout')]
class ProductManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $status = '';
    public $pagination = 12;

    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function render()
    {
        $products = Product::query()
            ->with(['categories', 'images'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->category);
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->pagination);

        return view('livewire.tenant.backend.products.product-management', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }
}
