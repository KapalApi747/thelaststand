<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomer
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access to verification routes only if the customer is authenticated
        if ($request->routeIs('customer-verification.*')) {
            if (!Auth::guard('customer')->check()) {
                return redirect()->route('login');
            }

            return $next($request);
        }

        // All other routes: must be logged in as customer
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

