<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	
	class AuthController extends Controller
	{
		public function __construct()
		{
			$this->middleware('guest')->except('logout');
		}
		
		public function login()
		{
			return view('accounting.auth.login');
		}
		//
	}
