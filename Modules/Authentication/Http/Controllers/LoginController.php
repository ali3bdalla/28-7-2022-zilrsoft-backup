<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LoginController extends Controller
{
    /*
		|--------------------------------------------------------------------------
		| Login Controller
		|--------------------------------------------------------------------------
		|
		| This controller handles authenticating users for the application and
		| redirecting them to your home screen. The controller uses a trait
		| to conveniently provide its functionality to your applications.
		|
		*/

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = "";



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->redirectTo = route('dashboard.index');
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return Response
     */
    public function showLoginForm()
    {
        return view('authentication::login');
    }
}
