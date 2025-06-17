<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionCookie
{
    public function handle($request, Closure $next)
    {
        $host = strtolower(trim($request->getHost()));

        if ($host === 'thelaststand.local') {
            Config::set('session.cookie', 'central_session');
            Config::set('session.domain', 'thelaststand.local');  // root domain, ok
            //Log::info("SessionCookie middleware: Set cookie to central_session for host {$host}");
        } elseif (str_ends_with($host, '.thelaststand.local')) {
            Config::set('session.cookie', 'tenant_session');
            Config::set('session.domain', null); // **host-only cookie for tenant subdomain**
            //Log::info("SessionCookie middleware: Set cookie to tenant_session (host-only) for host {$host}");
        } else {
            Config::set('session.cookie', 'laravel_session');
            Config::set('session.domain', null);
            //Log::info("SessionCookie middleware: Set cookie to laravel_session for host {$host}");
        }

        return $next($request);
    }

}
