<?php

namespace App\Livewire\Tenant\Backend\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('t-dashboard-layout')]
class ProductCreation extends Component
{
    use WithFileUploads;

    public $sku;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $is_active = true;
    public $images = [];
    public $categoryIds = [];
    public $allCategories;

    protected function rules()
    {
        return [
            'sku' => 'required|string|max:100|unique:products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'categoryIds' => 'required|array|min:1',
            'categoryIds.*' => 'exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function mount()
    {
        $this->allCategories = Category::all();
    }

    public function saveProduct()
    {
        $this->validate();

        $product = Product::create([
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
        ]);

        foreach ($this->images as $index => $image) {
            $storagePath = $image->store('assets/img/products', 'tenancy');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $storagePath,
                'is_main_image' => $index === 0 && $product->images()->count() === 0, // First image = main if none exists
            ]);
        }

        $product->categories()->sync($this->categoryIds);

        session()->flash('message', 'Product created successfully!');
        $this->reset(['sku', 'name', 'description', 'categoryIds', 'price', 'stock', 'is_active', 'images']);
    }

    public function render()
    {
        return view('livewire.tenant.backend.products.product-creation');
    }
}
