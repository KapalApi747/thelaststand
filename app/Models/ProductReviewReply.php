<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReviewReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_review_replies';

    protected $fillable = [
        'product_review_id',
        'customer_id',
        'body',
    ];

    public function review()
    {
        return $this->belongsTo(ProductReview::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
