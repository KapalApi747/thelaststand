<?php

namespace App\Livewire\Tenant\Frontend\Main;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('t-shop-layout')]
class ShopProducts extends Component
{
    use WithPagination;

    public $categories;

    public $search = '';
    public $selectedCategories = [];
    public $minPrice = null;
    public $maxPrice = null;

    public function mount()
    {
        $this->categories = Category::with('children')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatedMinPrice($value)
    {
        $this->minPrice = $value !== '' ? $value : null;
        $this->resetPage();
    }

    public function updatedMaxPrice($value)
    {
        $this->maxPrice = $value !== '' ? $value : null;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with([
            'images',
            'categories',
            'variants',
            'variants.images',
        ])
            ->withCount([
                'approvedReviews', // gives: approved_reviews_count
            ])
            ->withAvg([
                'approvedReviews' => fn ($q) => $q->where('is_approved', true)
            ], 'rating') // gives: approved_reviews_avg_rating
            ->where('is_active', 1);

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedCategories)) {
            foreach ($this->selectedCategories as $categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }
        }

        if ($this->minPrice !== null) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice !== null) {
            $query->where('price', '<=', $this->maxPrice);
        }

        return view('livewire.tenant.frontend.main.shop-products', [
            'products' => $query->paginate(12),
            'categories' => $this->categories,
        ]);
    }
}
