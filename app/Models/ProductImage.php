<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'product_id',
        'path',
        'is_main_image',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
