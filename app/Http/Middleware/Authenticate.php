<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function redirectTo($request): string
    {
        $request->session()->put('url.intended', URL::current());
        if (strpos(url()->current(), 'web'))
            return '/web/sign_in';

        return route('login');
    }
}
