<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticateTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('Auth check (tenant): ' . (Auth::guard('tenant')->check() ? 'yes' : 'no'));

        /*dd([
            'tenancy_initialized' => tenancy()->initialized,
            'session' => session()->all(),
            'tenant_guard_user' => session('login_tenant_' . sha1(User::class)),
            'authenticated_user' => Auth::guard('tenant')->user(),
        ]);*/

        // Check if tenant guard is authenticated
        if (!Auth::guard('tenant')->check()) {
            Log::info('Current guard: ' . Auth::guard()->getName());

            return redirect()->route('tenant.test');
        }

        return $next($request);
    }
}

