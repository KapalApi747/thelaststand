<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $connection = 'mysql';

    protected $table = 'payouts';

    protected $fillable = [
        'tenant_id',
        'amount',
        'status',
        'paid_at',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }
}
