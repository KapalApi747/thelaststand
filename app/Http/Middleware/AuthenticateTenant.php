<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateTenant
{
    public function handle(Request $request, Closure $next)
    {
        // Optional: specify a custom guard if you're using one
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

