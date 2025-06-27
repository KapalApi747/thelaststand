<?php

namespace App\Livewire\Tenant\Backend\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('t-dashboard-layout')]
class ProductEdit extends Component
{
    use WithFileUploads;

    public $product;
    public $sku;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $is_active;
    public $allCategories;
    public $categoryIds = [];
    public $newImages = [];
    public $existingImages = [];

    public function mount(Product $product)
    {
        $product->load('images', 'categories');
        $this->product = $product;

        $this->allCategories = Category::with([
            'children',
            'children.children',
            'children.children.children',
        ])->whereNull('parent_id')->get();

        $this->categoryIds = $this->product->categories->pluck('id')->toArray();

        $this->sku = $this->product->sku;
        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->price = $this->product->price;
        $this->stock = $this->product->stock;
        $this->is_active = $this->product->is_active ? '1' : '0';
        $this->existingImages = $product->images->toArray();
    }

    public function rules()
    {
        return [
            'sku' => 'required|string|max:100|unique:products,sku,' . $this->product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'categoryIds' => 'required|array|min:1',
            'categoryIds.*' => 'exists:categories,id',
            'newImages.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function updatedPrice($value)
    {
        $this->price = is_numeric($value) ? floatval($value) : null;
    }

    public function setMainImage($imageId)
    {
        $image = ProductImage::find($imageId);

        if (!$image || $image->product_id !== $this->product->id) return;

        // Set all other images to false
        ProductImage::where('product_id', $this->product->id)
            ->update(['is_main_image' => false]);

        // Set this image to true
        $image->update(['is_main_image' => true]);

        $this->existingImages = $this->product->fresh()->images->toArray();
    }


    public function deleteImage($imageId)
    {
        $image = ProductImage::find($imageId);

        if($image && $image->product_id == $this->product->id) {
            Storage::disk('tenancy')->delete($image->path);
            $image->delete();

            $this->existingImages = $this->product->fresh()->images->toArray();
        }
    }

    public function updateProduct()
    {
        $this->validate();

        $this->product->update([
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
        ]);

        foreach ($this->newImages as $index => $image) {
            $storagePath = $image->store('assets/img/products', 'tenancy');

            ProductImage::create([
                'product_id' => $this->product->id,
                'path' => $storagePath,
                'is_main_image' => $index === 0 && $this->product->images()->count() === 0, // First image = main if none exists
            ]);
        }

        $this->newImages = [];
        $this->existingImages = $this->product->fresh()->images->toArray();

        $this->product->categories()->sync($this->categoryIds);

        session()->flash('message', 'Product updated successfully!');
    }

    public function render()
    {
        return view('livewire.tenant.backend.products.product-edit');
    }
}
