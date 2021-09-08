<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Jobs\Accounting\Chart\CreateAmericanChartOfAccountsJob;
use App\Models\Country;
use App\Models\Manager;
use App\Models\Organization;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

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
    protected $redirectTo = '/dashboard';

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
     * @return \Inertia\Response
     */
    public function showRegistrationForm(): \Inertia\Response
    {
        return Inertia::render(
            "Auth/RegisterPage", [
                'types' => Type::all(),
                'countries' => Country::all(),
            ]
        );
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request): Response
    {

        $this->validator($request->all())->validate();

        DB::beginTransaction();
        try {
            $user = $this->create($request->all());
            DB::commit();
            event(new Registered($user));
            $this->guard()->login($user);
            return Inertia::location('/dashboard');
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make(
            $data, [
                'title' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'description' => 'nullable',
                'vat_number' => 'required|string|max:255',
                'cr_number' => 'required|string|max:255',
                'country_id' => 'required|integer|exists:countries,id',
                'business_type_id' => 'required|integer|exists:types,id',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:managers',
                'password' => 'required|string|min:8|confirmed',
            ]
        );
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

        $organization = Organization::create(
            [
                'title' => $data['title'],
                'title_ar' => $data['title'],
                'description' => $data['description'],
                'description_ar' => $data['description'],
                'country_id' => $data['country_id'],
                'type_id' => $data['business_type_id'],
                'type' => 'establishment', //$data['org_type'],
                'cr' => $data['cr_number'],
                'vat' => $data['vat_number'],
                'phone_number' => $data['phone_number'],
                'city' => $data['city'],
                'city_ar' => $data['city'],
                'address' => $data['address'],
                'address_ar' => $data['address'],
            ]
        );

        $user = User::create(
            [
                'organization_id' => $organization->id,
                'is_supervisor' => true,
                'is_manager' => true,
                'is_system_user' => true,
                'name' => $data['name'],
                'name_ar' => $data['name'],
                'phone_number' => $data['phone_number'],
                'user_type' => 'individual',
            ]
        );

        $manager = Manager::create(
            [
                'organization_id' => $organization->id,
                'branch_id' => 0,
                'department_id' => 0,
                'email' => $data['email'],
                'name' => $data['name'],
                'name_ar' => $data['name'],
                'password' => Hash::make($data['password']),
                'user_id' => $user->id,
            ]
        );

        $organization->fill(['supervisor_id' => $user->id]);

        $organization->save();

        dispatch_now(new CreateAmericanChartOfAccountsJob($organization, $manager));

        $manager->assignRole('super admin');


        return $manager;
    }

}
