<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $table = 'shipping_methods';

    protected $fillable = [
        'code',
        'label',
        'cost',
        'carriers',
        'enabled',
    ];

    protected $casts = [
        'carriers' => 'array',
        'enabled' => 'boolean',
    ];
}
