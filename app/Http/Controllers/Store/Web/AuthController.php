<?php

namespace App\Http\Controllers\Store\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Web\AuthLoginRequest;
use App\Http\Requests\Store\Web\ChangePasswordRequest;
use App\Http\Requests\Store\Web\ForgetPasswordRequest;
use App\Http\Requests\Store\Web\RegistrationRequest;
use App\Http\Requests\Store\Web\VerifyVerificationCodeRequest;
use App\Models\User;
use App\Notifications\Store\StoreWelcomeNotification;
use App\Notifications\User\PasswordResetVerificationCodeNotification;
use App\Notifications\User\SignUpPhoneNumberVerificationCodeNotification;
use App\Repository\UserRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AuthController extends Controller
{
    private UserRepositoryContract $userRepositoryContract;

    public function __construct(UserRepositoryContract $userRepositoryContract)
    {
        $this->userRepositoryContract = $userRepositoryContract;
    }

    public function signInPage(): Response
    {
        return Inertia::render('Auth/SignIn');
    }

    public function signIn(AuthLoginRequest $request)
    {
        $phoneNumber = $request->getPhoneNumber();
        $password = $request->getPassword();
        if ($this->userRepositoryContract->isLoggedAsOnlineUser($phoneNumber, $password)) {
            return redirect()->intended('/web');
        }
        abort(403);
    }


    public function signUpPage(): Response
    {
        return Inertia::render('Auth/SignUp');
    }

    /**
     * @throws Throwable
     */
    public function signUp(RegistrationRequest $request): Response
    {
        $phoneNumber = $request->getPhoneNumber();
        $password = $request->getPassword();
        $firstName = $request->getFirstName();
        $lastName = $request->getLastName();
        $countryCode = $request->getCountryCode();
        $request->ensureIsValidPhoneNumber($this->userRepositoryContract);
        $user = $this->userRepositoryContract->createUser($phoneNumber, $password, $firstName, $lastName, $countryCode);
        $user->notify(new SignUpPhoneNumberVerificationCodeNotification());
        return Inertia::render('Auth/VerificationCodeSent');
    }

    /**
     * @throws Throwable
     */
    public function verify(VerifyVerificationCodeRequest $request): Response
    {
        $request->ensureIsValidUrl();
        $verificationCode = $request->getVerificationCode();
        $userId = $request->getId();
        $verifiedUser = $this->userRepositoryContract->verifyUser($userId, $verificationCode);
        $verifiedUser->notify(new StoreWelcomeNotification());
        Auth::guard('client')->login($verifiedUser);
        return Inertia::render('Auth/Verified');
    }

    public function forgetPasswordPage(): Response
    {
        return Inertia::render('Auth/ForgetPassword');
    }

    public function forgetPassword(ForgetPasswordRequest $request): Response
    {
        $phoneNumber = $request->getPhoneNumber();
        $user = $this->userRepositoryContract->createForgetPasswordVerificationCode($phoneNumber);
        $user->notify(new PasswordResetVerificationCodeNotification());
        return Inertia::render('Auth/VerificationCodeSent');
    }

    /**
     * @throws Throwable
     */
    public function resetPasswordPage(VerifyVerificationCodeRequest $request): Response
    {
        $verificationCode = $request->getVerificationCode();
        $id = $request->getId();
        $request->ensureIsValidUrl();
        $user = User::findOrFail($id);
        $this->userRepositoryContract->ensureIsValidVerificationCode($user, $verificationCode);
        return Inertia::render('Auth/ResetPassword', ['queryString' => $request->getQueryString()]);
    }

    /**
     * @throws Throwable
     */
    public function confirmResetPassword(ChangePasswordRequest $request): Response
    {
        $verificationCode = $request->getVerificationCode();
        $password = $request->getPassword();
        $user = User::findOrFail($request->getId());
        $request->ensureIsValidUrl();
        $this->userRepositoryContract->ensureIsValidVerificationCode($user, $verificationCode);
        $this->userRepositoryContract->changeUserPassword($user, $password);
        return Inertia::render('Auth/PasswordChanged');
    }
}
