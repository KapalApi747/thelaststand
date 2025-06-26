<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerEmailVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('customer') && ! $request->user('customer')->hasVerifiedEmail()) {
            return redirect()->route('customer-verification.notice');
        }

        return $next($request);
    }
}
