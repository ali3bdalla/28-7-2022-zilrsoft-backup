<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\Accounting\Chart\CreateAmericanChartOfAccountsJob;
use App\Models\Country;
use App\Models\Manager;
use App\Models\Organization;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/accounting/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return Factory|View
     */
    public function showRegistrationForm()
    {

        $countries = Country::all();
        $types = Type::all();
        return view('accounting.auth.register', compact('types', 'countries'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        DB::beginTransaction();
        try {
            $user = $this->create($request->all());
            DB::commit();
        } catch (ValidationException $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return $e->getMessage();
            }
            return Redirect::to('/')
                ->withErrors($e->getErrors())
                ->withInput();

        } catch (Exception $e) {
            // dd($e->getMessage());
            DB::rollback();

            if ($request->expectsJson()) {
                return $e->getMessage();
            }

            return Redirect::to('/')
                ->withInput();
        }
        event(new Registered($user));
        $this->guard()->login($user);

        return $this->registered($request, $user)
        ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'organization_title' => 'required|string|max:255',
            'organization_title_ar' => 'required|string|max:255',
            'organization_city' => 'required|string|max:255',
            'organization_city_ar' => 'required|string|max:255',
            'organization_address' => 'required|string|max:255',
            'organization_address_ar' => 'required|string|max:255',
            'organization_phone_number' => 'required|string|max:255',
            'organization_vat' => 'required|numeric|max:255',
            'organization_cr' => 'required|max:255',
            'organization_country_id' => 'required|integer|exists:countries,id',
            'organization_business_type' => 'required|integer|exists:types,id',
            'organization_type' => 'required|string|max:255',
            'organization_description' => 'required',
            'organization_description_ar' => 'required',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managers',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {

        $organization = Organization::create([
            'title' => $data['organization_title'],
            'title_ar' => $data['organization_title_ar'],
            'description' => $data['organization_description'],
            'description_ar' => $data['organization_description_ar'],
            'country_id' => $data['organization_country_id'],
            'type_id' => $data['organization_business_type'],
            'type' => $data['organization_type'],
            'cr' => $data['organization_cr'],
            'vat' => $data['organization_vat'],
            'phone_number' => $data['organization_phone_number'],
            'city' => $data['organization_city'],
            'city_ar' => $data['organization_city_ar'],
            'address' => $data['organization_address'],
            'address_ar' => $data['organization_address_ar'],
        ]);
        $user = User::create([
            'organization_id' => $organization->id,
            'is_supervisor' => true,
            'is_manager' => true,
            'is_system_user' => true,
            'name' => $data['name'],
            'name_ar' => $data['name_ar'],
            'phone_number' => $data['phone_number'],
            'user_type' => 'individual',
        ]);
        $manager = Manager::create([
            'organization_id' => $organization->id,
            'branch_id' => 0,
            'department_id' => 0,
            'email' => $data['email'],
            'name' => $data['name'],
            'name_ar' => $data['name_ar'],
            'password' => Hash::make($data['password']),
            'user_id' => $user->id,
        ]);

        $organization->fill(['supervisor_id' => $user->id]);
        $organization->save();
        dispatch_now(new CreateAmericanChartOfAccountsJob($organization,$manager));
        return $manager;
    }

}
