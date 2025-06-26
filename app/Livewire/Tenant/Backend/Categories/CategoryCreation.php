<?php

namespace App\Livewire\Tenant\Backend\Categories;

use App\Models\Category;
use Livewire\Component;

class CategoryCreation extends Component
{
    public $name;
    public $parent_id = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    public function saveCategory()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
        ]);

        session()->flash('message', 'Category created successfully!');
        $this->reset(['name', 'parent_id']);
    }

    public function render()
    {
        return view('livewire.tenant.backend.categories.category-creation', [
            'allCategories' => Category::orderBy('name')->get(),
        ]);
    }
}
