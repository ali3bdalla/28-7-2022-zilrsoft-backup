<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

    }

    public function register(RegisterRequest $request)
    {
        return $request->save();
    }
    //
}
