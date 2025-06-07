<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReviewReply extends Model
{
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
