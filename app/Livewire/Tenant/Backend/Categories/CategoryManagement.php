<?php

namespace App\Livewire\Tenant\Backend\Categories;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class CategoryManagement extends Component
{
    public function render()
    {
        return view('livewire.tenant.backend.categories.category-management', [
            'categories' => Category::all()
        ]);
    }
}
