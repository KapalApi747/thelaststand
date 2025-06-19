<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
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
