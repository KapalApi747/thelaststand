<?php

use Illuminate\Support\Str;

if (!function_exists('tenantPlaceholders')) {
    function tenantPlaceholders(string $content): string
    {
        $tenant = tenant();

        return strtr($content, [
            '{{store_logo_path}}'  => $tenant->logo_path,
            '{{store_name}}'       => $tenant->store_name ?? '',
            '{{store_email}}'      => $tenant->profile->email ?? '',
            '{{store_phone}}'      => $tenant->profile->phone ?? '',
            '{{store_address}}'    => $tenant->profile->address ?? '',
            '{{store_city}}'       => $tenant->profile->city ?? '',
            '{{store_state}}'      => $tenant->profile->state ?? '',
            '{{store_zip}}'        => $tenant->profile->zip ?? '',
            '{{store_country}}'    => $tenant->profile->country ?? '',
            '{{store_vat}}'        => $tenant->vat_id ?? '',
            '{{store_created_at}}' => $tenant->created_at ?? '',
        ]);
    }
}
