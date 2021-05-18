<?php

namespace App\Http\Middleware;

use App\Models\Manager;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('token') && $request->filled('token'))
        {
            $manager = Manager::where('api_token',$request->input('token'))->first();
            if($manager) {
                Auth::login($manager);
                return $next($request);

            }
        }

        throw new AuthenticationException(
            'Unauthenticated.', ['managet'], '/login'
        );

    }
}
