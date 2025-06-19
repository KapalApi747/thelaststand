<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, SoftDeletes;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'plan',
            'store_name',
            'logo_path',
        ];
    }

    public function profile() {
        return $this->hasOne(TenantProfile::class, 'tenant_id', 'id');
    }

    public function payouts() {
        return $this->hasMany(Payout::class, 'tenant_id', 'id');
    }
}
