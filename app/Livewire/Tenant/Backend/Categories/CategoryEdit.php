<?php

namespace App\Livewire\Tenant\Backend\Categories;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class CategoryEdit extends Component
{
    public Category $category;
    public $parent_id;
    public $name;
    public $allCategories;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->parent_id = $category->parent_id;
        $this->name = $category->name;
        $this->allCategories = Category::where('id', '!=', $category->id)->get();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            ];
    }

    public function updateCategory()
    {
        $this->validate();

        $this->category->update([
            'parent_id' => $this->parent_id,
            'name' => $this->name,
        ]);

        session()->flash('message', 'Category updated successfully!');
        return redirect()->route('tenant-dashboard.category-management');
    }

    public function render()
    {
        return view('livewire.tenant.backend.categories.category-edit');
    }
}
