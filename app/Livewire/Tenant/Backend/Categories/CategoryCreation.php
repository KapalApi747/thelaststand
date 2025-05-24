<?php

namespace App\Livewire\Tenant\Backend\Categories;

use App\Models\Category;
use Livewire\Component;

class CategoryCreation extends Component
{
    public $name;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function saveCategory()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Category created successfully!');
        $this->reset(['name']);
    }

    public function render()
    {
        return view('livewire.tenant.backend.categories.category-creation');
    }
}
