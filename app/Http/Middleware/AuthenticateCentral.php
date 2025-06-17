<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateCentral
{
    public function handle(Request $request, Closure $next)
    {
        // Optional: specify a custom guard if you're using one
        if (!Auth::guard('central')->check()) {
            return redirect()->route('central-login');
        }

        return $next($request);
    }
}

