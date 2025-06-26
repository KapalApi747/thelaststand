<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware om de session cookie naam en domein dynamisch aan te passen
 * op basis van de host (domein) waarop de applicatie draait.
 *
 * Gedrag:
 * - Voor het centrale domein 'thelaststand.local' wordt de cookie ingesteld
 *   als 'central_session' met domein 'thelaststand.local' (root domein).
 * - Voor tenant-subdomeinen (zoals tenant1.thelaststand.local) wordt een
 *   host-only cookie 'tenant_session' ingesteld zonder domeinnaam, zodat de
 *   cookie alleen geldig is voor dat subdomein.
 * - Voor andere hosts wordt de standaard Laravel sessie cookie gebruikt.
 *
 * Dit voorkomt conflicten tussen sessies van verschillende tenants en het centrale systeem.
 */

class SessionCookie
{
    public function handle($request, Closure $next)
    {
        $host = strtolower(trim($request->getHost()));

        if ($host === 'thelaststand.local') {
            Config::set('session.cookie', 'central_session');
            Config::set('session.domain', 'thelaststand.local');
        } elseif (str_ends_with($host, '.thelaststand.local')) {
            Config::set('session.cookie', 'tenant_session');
            Config::set('session.domain', null);
        } else {
            Config::set('session.cookie', 'laravel_session');
            Config::set('session.domain', null);
        }

        return $next($request);
    }

}
