<?php

namespace App\Livewire\Tenant\Backend\Categories;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class CategoryManagement extends Component
{
    public $categories;

    protected $listeners = ['confirmDelete' => 'deleteCategory'];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::with('children')->whereNull('parent_id')->get();
    }

    #[On('category-delete-requested')]
    public function confirmDelete($id)
    {
        $category = Category::findOrFail($id);

        if ($category) {
            $category->delete();
            session()->flash('message', 'Category deleted successfully.');
        }

        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.tenant.backend.categories.category-management');
    }
}
