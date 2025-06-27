<?php

namespace App\Models;

use App\Notifications\CustomerVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($customer) {
            $customer->slug = Str::slug($customer->name);
        });

        static::updating(function ($customer) {
            $customer->slug = Str::slug($customer->name);
        });
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomerVerifyEmail());
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function paymentAccounts()
    {
        return $this->hasMany(CustomerPaymentAccount::class);
    }

    public function getPaymentAccount(string $provider): ?CustomerPaymentAccount
    {
        return $this->paymentAccounts()->where('provider', $provider)->first();
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function billingAddress(): ?CustomerAddress
    {
        return $this->addresses()->where('type', 'billing')->first();
    }

    public function shippingAddress(): ?CustomerAddress
    {
        return $this->addresses()->where('type', 'shipping')->first();
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function reviewReplies()
    {
        return $this->hasMany(ProductReviewReply::class);
    }

}
