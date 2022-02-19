<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spinen\QuickBooks\Http\Middleware\Filter;

class QuickBooksAuthenticationMiddleware extends Filter
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->organization_id === 1) {
            return parent::handle($request, $next);
        }
        return $next($request);
    }
}
