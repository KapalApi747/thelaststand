<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check() && !Auth::guard('web')->check()) {
            return redirect()->route('shop.login');
        }

        return $next($request);
    }
}

