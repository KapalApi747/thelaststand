<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomer
{
    public function handle(Request $request, Closure $next)
    {
        $customer = $this->resolveCustomer();

        // Verification routes: allow only if customer exists
        if ($request->routeIs('customer-verification.*')) {
            if (! $customer) {
                return redirect()->route('login');
            }

            return $next($request);
        }

        // All other routes require a valid customer
        if (! $customer) {
            return redirect()->route('login');
        }

        return $next($request);
    }

    protected function resolveCustomer()
    {
        if ($customer = Auth::guard('customer')->user()) {
            return $customer;
        }

        if ($tenantUser = Auth::guard('web')->user()) {
            return $tenantUser->customers()->first(); // assumes one-to-one or primary customer
        }

        return null;
    }
}

