<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;

class FrontEndMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	Inertia::setRootView('web');
        return $next($request);
    }
}
