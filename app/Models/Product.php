<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'is_active',
    ];

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'product_tenant', 'product_id', 'tenant_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main_image', true);
    }
}
