<?php
	
	namespace App\Http\Controllers\Web;
	
	use App\Http\Controllers\Controller;
	use App\Models\Manager;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Inertia\Inertia;
	
	class AuthController extends Controller
	{
		
		public function signInPage()
		{
			return Inertia::render('Web/Auth/SignIn');
		}
		
		
		public function signIn()
		{
		
		}
		
		
		public function signUpPage()
		{
			return Inertia::render('Web/Auth/SignUp');
			
		}
		
		public function signUp(Request $request)
		{
			$request->validate(
				[
					'username' => 'required|string',
					'phone_number' => 'required|string|unique:phone_number',
				]
			);
			
			
			$otp = generateOtp();
			DB::table('online_users_placeholder')->insert(
				[
					'phone_number' => $request->input('phone_number'),
					'username' => $request->input('username'),
					'otp' => $otp,
				]
			);
			sendOtp($request->input('phone_number'), $otp);
//			$manager = Manager::find(1);

//			$client = $manager->organization()->users()->create(
//				[
//					'name' => $request->input('username'),
//					'ar_name' => $request->input('username'),
//					'country_code' => '+966',
//					'creator_id' => $manager->id,
//					'user_slug' => 'online_user',
//					'is_client' => true,
//					'user_type' => 'individual',
//					'user_title' => 'mis',
//				]
//			);
//
//
			
		}
		
		
		//
	}
