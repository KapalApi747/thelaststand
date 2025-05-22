<?php

namespace App\Livewire\Tenant\Backend;

use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreation extends Component
{
    use WithFileUploads;

    public $sku;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $status = 'draft';
    public $images = [];

    protected function rules()
    {
        return [
            'sku' => 'required|string|max:100|unique:mysql.products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:draft,active',
            'images.*' => 'image|max:2048',
        ];
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
            'status' => $this->status,
        ]);

        tenant()->products()->attach($product->id);

        foreach ($this->images as $image) {
            $storagePath = $image->store('assets/img/products', 'tenancy');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $storagePath,
            ]);
        }

        session()->flash('message', 'Product created successfully!');
        $this->reset(['name', 'description', 'price', 'status', 'images']);
    }

    public function render()
    {
        return view('livewire.tenant.backend.product-creation');
    }
}
