<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TenantDeletionService
{
    public function deleteTenant(Tenant $tenant): bool
    {
        DB::beginTransaction();

        try {
            // 1. Drop the tenant's database
            $databaseName = $tenant->tenancy_db_name; // Or dynamically construct if you're using a naming convention
            DB::statement("DROP DATABASE IF EXISTS `$databaseName`");

            // 2. Remove associated storage directories
            $this->deleteTenantStorage($tenant->id); // or $tenant->getTenantKey()

            // 3. Delete the tenant from the central database
            $tenant->forceDelete();

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }

    protected function deleteTenantStorage(string|int $tenantId): void
    {
        $paths = [
            storage_path("app/tenancy/tenant{$tenantId}"),
            storage_path("tenant{$tenantId}"),
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                File::deleteDirectory($path);
            }
        }
    }
}
