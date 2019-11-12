<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role)
    {
        
        if(!Auth::user()->isAuthorizedTo($role)){
           abort(503,trans('message.no_permission',['permission'=>strtolower($role)])); 
        }

        return $next($request);
    }
}
