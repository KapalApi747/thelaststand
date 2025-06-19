<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class OnlyTenancyOnTenantDomain extends InitializeTenancyByDomain
{
    protected array $centralDomains = [
        'thelaststand.local',
    ];

    public function handle($request, Closure $next)
    {
        if (in_array($request->getHost(), $this->centralDomains)) {
            return $next($request); // Skip tenancy initialization
        }

        // Initialize tenancy as usual
        return parent::handle($request, $next);
    }
}
