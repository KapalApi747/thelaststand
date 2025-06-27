<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerEmailVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        $customer = $this->resolveCustomer($request);

        if ($customer && ! $customer->hasVerifiedEmail()) {
            return redirect()->route('customer-verification.notice');
        }

        return $next($request);
    }

    protected function resolveCustomer(Request $request)
    {
        // Try customer guard first
        if ($customer = auth('customer')->user()) {
            return $customer;
        }

        // Fallback: logged-in tenant user with attached customer
        if ($tenantUser = auth('web')->user()) {
            return $tenantUser->customers()->first();
        }

        return null;
    }
}
