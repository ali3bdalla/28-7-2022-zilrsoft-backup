<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $lang
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $lang)
    {
        app()->setLocale($lang);
        return $next($request);
    }
}
