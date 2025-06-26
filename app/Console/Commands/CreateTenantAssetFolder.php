<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateTenantAssetFolder extends Command
{
    /**
     * De naam en signatuur van het Artisan-commando.
     *
     * @var string
     */
    protected $signature = 'tenancy:create-tenant-asset-folder';

    /**
     * De beschrijving van het Artisan-commando.
     *
     * @var string
     */
    protected $description = 'Maak asset-mappen aan voor elke tenant!';

    /**
     * Voer het Artisan-commando uit.
     */
    public function handle()
    {
        // Basispad voor tenant-bestanden
        $basePath = storage_path('app/tenancy');

        // Haal alle tenants op uit de centrale database
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $tenantId = $tenant->id;

            // Bouw het volledige pad naar de asset-map van de tenant
            $path = $basePath . DIRECTORY_SEPARATOR . $tenantId . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img';

            // Maak de map aan als die nog niet bestaat
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->info("Asset-map aangemaakt voor tenant: {$tenantId}");
            } else {
                $this->info("Asset-map bestaat al voor tenant: {$tenantId}");
            }
        }

        $this->info('Tenant asset-mappen zijn aangemaakt.');

        return 0;
    }
}
