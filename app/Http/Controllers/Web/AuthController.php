<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\OnlineUserPlaceholder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{

	public function signInPage()
	{
		return Inertia::render('Web/Auth/SignIn');
	}


	public function signIn(Request $request)
	{
		$request->validate(
			[
				'phone_number' => 'required|mobileNumber|exists:users,phone_number',
				'password' => 'required|string|min:6',
			]
		);

		User::where('phone_number', $request->input('phone_number'))->first();

		if (auth('client')->attempt(['phone_number' => $request->input('phone_number'), 'password' => $request->input('password')])) {
			return redirect(route('web.index'));
		}


		throw  ValidationException::withMessages(['phone_number' => 'Phone Number Or password Are Not Valid']);
	}


	public function signUpPage()
	{
		return Inertia::render('Web/Auth/SignUp');
	}

	public function signUp(Request $request)
	{
		$request->validate(
			[
				'first_name' => 'required|alpha',
				'last_name' => 'required|alpha',
				'phone_number' => 'required|string|mobileNumber|unique:users,phone_number',
				'password' => 'required|string|min:6',
			]
		);


		$otp = generateOtp();

		if (strlen($request->input('phone_number')) == 10) {
			$phoneNumber = substr($request->input('phone_number'), 1, strlen($request->input('phone_number')));
		} else {
			$phoneNumber = $request->input('phone_number');
		}

		OnlineUserPlaceholder::create(
			[

				'phone_number' => $phoneNumber,
				'first_name' => $request->input('first_name'),
				'last_name' => $request->input('last_name'),
				'password' => bcrypt($request->input('password')),
				'otp' => $otp

			]
		);

		sendOtp($phoneNumber, $otp);
		Session::put('sign_up_phone_number', $phoneNumber);
		return redirect(route('web.sign_up.confirm_sign_up'));
	}


	public function confirmSignUpPage()
	{

		return Inertia::render(
			'Web/Auth/ConfirmOtp',
			[
				'phone_number' => Session::get('sign_up_phone_number', ""),
				'validate_url' => '/web/sign_up/confirm_sign_up'
			]
		);
	}

	public function confirmSignUp(Request $request)
	{
		$request->validate(
			[
				'otp' => 'required|string|exists:online_users_placeholder,otp',
				'phone_number' => 'required|mobileNumber|string|exists:online_users_placeholder,phone_number',
			]
		);
		$placeholderUser = OnlineUserPlaceholder::where('phone_number', $request->input('phone_number'))->orderByDesc('id')->first();

		if ($placeholderUser && $placeholderUser->otp == $request->input('otp')) {
			$manager = Manager::find(1);
			$client = $manager->organization->users()->create(
				[
					'name' => $placeholderUser->first_name . ' ' . $placeholderUser->last_name,
					'name_ar' => $placeholderUser->first_name . ' ' . $placeholderUser->last_name,
					'first_name' => $placeholderUser->first_name,
					'last_name' => $placeholderUser->last_name,
					'phone_number' => $placeholderUser->phone_number,
					'password' => $placeholderUser->password,
					'country_code' => '+966',
					'creator_id' => $manager->id,
					'user_slug' => 'online_user',
					'is_client' => true,
					'user_type' => 'individual',
					'user_title' => 'mr',
				]
			);
			OnlineUserPlaceholder::where('phone_number', $request->input('phone_number'))->delete();
			auth('client')->login($client);
			return redirect('/web');
		} else {
		}
	}


	public function resendOtp(Request $request)
	{
		$request->validate(
			[
				'phone_number' => 'required|mobileNumber|string|exists:online_users_placeholder,phone_number',
			]
		);
		$placeholderUser = OnlineUserPlaceholder::where('phone_number', $request->input('phone_number'))->orderByDesc('id')->first();

		$otp = generateOtp();
		$placeholderUser->update([
			'otp' => $otp
		]);
		sendOtp($request->input('phone_number'), $otp);
		// return back();
	}
















	public function forgetPasswordPage()
	{
		return Inertia::render('Web/Auth/ForgetPassword');
	}

	public function forgetPassword(Request $request)
	{
		$request->validate(
			[

				'phone_number' => 'required|string|mobileNumber|exists:users,phone_number',

			]
		);

		$otp = generateOtp();

		if (strlen($request->input('phone_number')) == 10) {
			$phoneNumber = substr($request->input('phone_number'), 1, strlen($request->input('phone_number')));
		} else {
			$phoneNumber = $request->input('phone_number');
		}

		OnlineUserPlaceholder::create(
			[

				'phone_number' => $phoneNumber,
				'first_name' => "",
				'last_name' => "",
				'password' => "",
				'otp' => $otp

			]
		);

		sendOtp($phoneNumber, $otp);
		Session::put('reset_password_phone_number', $phoneNumber);
		return redirect('/web/forget_password/confirm');
	}


	public function confirmForgetPasswordPage()
	{

		return Inertia::render(
			'Web/Auth/ConfirmOtp',
			[
				'phone_number' => Session::get('reset_password_phone_number', ""),
				'validate_url' => '/web/forget_password/confirm'
			]
		);
	}

	public function confirmForgetPassword(Request $request)
	{
		$phoneNumber = Session::get('reset_password_phone_number', null);

		if (!$phoneNumber)
			return back();

		$request->validate(
			[
				'otp' => 'required|string|exists:online_users_placeholder,otp',
			]
		);
		$placeholderUser = OnlineUserPlaceholder::where('phone_number', $phoneNumber)->orderByDesc('id')->first();


		if ($placeholderUser && $placeholderUser->otp == $request->input('otp')) {
			Session::put('reset_password_otp', $placeholderUser->otp);
			return redirect('/web/forget_password/reset');
		} else {
			return back();
		}
	}





	public function resetPasswordPage()
	{

		return Inertia::render(
			'Web/Auth/ResetPassword',
			[

				'validate_url' => '/web/forget_password/reset'
			]
		);
	}

	public function confirmResetPassword(Request $request)
	{
		$otp = Session::get('reset_password_otp', null);
		$phoneNumber = Session::get('reset_password_phone_number', null);

		if (!$otp || !$phoneNumber)
			return back();


		$request->validate(
			[
				'password' => 'required|string|min:6|confirmed',
			]
		);

		$placeholderUser = OnlineUserPlaceholder::where('phone_number', $phoneNumber)->orderByDesc('id')->first();

		if ($placeholderUser && $placeholderUser->otp == $otp) {
			User::where([
				'phone_number' => $phoneNumber,

			])->update([
				'password' => Hash::make($request->input('password'))
			]);

			OnlineUserPlaceholder::where('phone_number', $phoneNumber)->delete();
			Session::put('reset_password_otp', null);
			Session::put('reset_password_phone_number', null);
			return redirect('/web/sign_in');
		} else {
			return back();
		}
	}
}
