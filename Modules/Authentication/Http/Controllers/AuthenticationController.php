<?php

namespace Modules\Authentication\Http\Controllers;

use App\Country;
use App\Type;
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
        $countries = Country::all();
        $types = Type::all();
        // return view('accounting.auth.register',compact('types','countries'));
        return view('authentication::register',compact('types','countries'));
    }

    public function performRegister()
    {

    }

    public function forgetPassword()
    {
        //
    }

}
