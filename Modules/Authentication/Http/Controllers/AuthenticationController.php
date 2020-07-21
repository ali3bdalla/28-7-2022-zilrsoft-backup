<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AuthenticationController extends Controller
{
  
    public function login()
    {
        return view('authentication::login');
    }

    public function performLogin()
    {
        //
    }
    
    public function register()
    {
        return view('authentication::register');
    }

    public function performRegister()
    {

    }

    public function forgetPassword()
    {
        //
    }

}
