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
    public $pagination = 10;
    public $categories;

    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function mount()
    {
        $this->categories = Category::with('children')->whereNull('parent_id')->get();
    }



    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedPagination()
    {
        $this->resetPage();
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
    }

    public function restoreProduct($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        session()->flash('message', 'Product restored successfully!');
    }

    public function render()
    {
        $products = Product::query()
            ->withTrashed()
            ->with(['categories', 'mainImage'])
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
        ]);
    }
}
