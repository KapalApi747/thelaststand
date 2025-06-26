<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TenantDeletionService
{
    /**
     * Verwijder een tenant volledig, inclusief database, opslagmappen en centrale record.
     *
     * @param \App\Models\Tenant $tenant
     * @return bool
     */
    public function deleteTenant(Tenant $tenant): bool
    {
        DB::beginTransaction();

        try {
            // 1. Verwijder de database van de tenant
            $databaseName = $tenant->tenancy_db_name; // Of bouw dynamisch op als je een conventie gebruikt
            DB::statement("DROP DATABASE IF EXISTS `$databaseName`");

            // 2. Verwijder gekoppelde opslagmappen van de tenant
            $this->deleteTenantStorage($tenant->id); // of $tenant->getTenantKey()

            // 3. Verwijder de tenant uit de centrale database (permanent)
            $tenant->forceDelete();

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            // Rapporteer eventuele fouten
            report($e);
            return false;
        }
    }

    /**
     * Verwijder opslagmappen die gekoppeld zijn aan een specifieke tenant-ID.
     *
     * @param string|int $tenantId
     * @return void
     */
    protected function deleteTenantStorage(string|int $tenantId): void
    {
        // Definieer mogelijke opslaglocaties voor deze tenant
        $paths = [
            storage_path("app/tenancy/tenant{$tenantId}"),
            storage_path("tenant{$tenantId}"),
        ];

        // Verwijder de mappen indien ze bestaan
        foreach ($paths as $path) {
            if (File::exists($path)) {
                File::deleteDirectory($path);
            }
        }
    }
}
