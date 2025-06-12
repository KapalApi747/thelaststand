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
        $this->products = Product::with([
            'images',
            'categories',
            'variants.images',
            'reviews' => function ($query) {
                $query->where('is_approved', true);
            }
        ])
            ->withCount([
                'reviews as approved_reviews_count' => fn ($query) => $query->where('is_approved', true)
            ])
            ->withAvg([
                'reviews as average_rating' => fn ($query) => $query->where('is_approved', true)
            ], 'rating')
            ->where('is_active', 1)
            ->get();
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
