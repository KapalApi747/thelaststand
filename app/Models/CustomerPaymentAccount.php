<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPaymentAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'provider',
        'provider_customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
