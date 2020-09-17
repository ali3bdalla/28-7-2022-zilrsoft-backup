<?php

namespace Modules\Authentication\Http\Controllers;
use App\Models\Country;
use App\Models\Type;
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