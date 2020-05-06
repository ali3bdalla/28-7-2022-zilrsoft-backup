<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProtectLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('protect_limit')
            && $request->session()->get('protect_limit', false)) {
            return $next($request);
        }

        if ($request->has('key') && $request->has('pass')) {
            $login = $request->input('key') == config('limit.key') ? $request->input('pass') == config('limit.pass') ? true : false : false;

            if ($login) {
                $request->session()->push('protect_limit', true);
                return $next($request);
            }
        }

        return redirect()->intended('/');
    }
}
