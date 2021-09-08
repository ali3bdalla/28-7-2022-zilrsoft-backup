<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $guard
     * @param string $redirectTo
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = "", $redirectTo = '/')
    {
        $isLogged = Auth::guard($guard)->check();

        if ($isLogged)
            return $redirectTo;

        return $next($request);
    }
}
