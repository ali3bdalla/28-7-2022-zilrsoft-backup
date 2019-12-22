<?php
	
	namespace App\Http\Controllers\Accounting\Auth;
	
	use App\Country;
	use App\Http\Controllers\Controller;
	use App\Manager;
	use App\Organization;
	use App\Type;
	use App\User;
	use Exception;
	use Illuminate\Auth\Events\Registered;
	use Illuminate\Foundation\Auth\RegistersUsers;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Redirect;
	use Illuminate\Support\Facades\Validator;
	
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
		
		public function showRegistrationForm()
		{
			$countries = Country::all();
			$types = Type::all();
			
			return view('auth.register',compact('types','countries'));
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
			try{
				$user = $this->create($request->all());
				DB::commit();
			}catch (ValidationException $e){
				DB::rollback();
				
				if ($request->expectsJson())
					return $e->getMessage();
				
				return Redirect::to(route('management.register'))
					->withErrors($e->getErrors())
					->withInput();
				
			}catch (Exception $e){
				// dd($e->getMessage());
				DB::rollback();
				
				if ($request->expectsJson())
					return $e->getMessage();
				
				return Redirect::to(route('management.register'))
					->withInput();
			}

//
//			if($request->expectsJson())
//				return $user;
			
			
			// $user = null;
			event(new Registered($user));
			$this->guard()->login($user);
			
			return $this->registered($request,$user)
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
			return Validator::make($data,[
				'org_title' => 'required|string|max:255',
				'org_title_ar' => 'required|string|max:255',
				'org_city' => 'required|string|max:255',
				'org_city_ar' => 'required|string|max:255',
				
				'org_address' => 'required|string|max:255',
				'org_address_ar' => 'required|string|max:255',
				
				
				'org_phone_number' => 'required|string|max:255',
				'org_description' => 'nullable',
				'org_description_ar' => 'nullable',
				'org_vat' => 'required|string|max:255',
				'org_cr' => 'required|string|max:255',
				'org_country_id' => 'required|integer|exists:countries,id',
				'org_business_type' => 'required|integer|exists:types,id',
				'org_type' => 'required|string|max:255',
				'org_description' => 'required',
				'org_description_ar' => 'required',
				
				'name' => 'required|string|max:255',
				'name_ar' => 'required|string|max:255',
				'phone_number' => 'required|string|max:255',
				'email' => 'required|string|email|max:255|unique:managers',
				'password' => 'required|string|min:8|confirmed'
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
				'title' => $data['org_title'],
				'title_ar' => $data['org_title_ar'],
				'description' => $data['org_description'],
				'description_ar' => $data['org_description_ar'],
				'country_id' => $data['org_country_id'],
				'type_id' => $data['org_business_type'],
				'type' => $data['org_type'],
				'cr' => $data['org_cr'],
				'vat' => $data['org_vat'],
				'phone_number' => $data['org_phone_number'],
				'city' => $data['org_city'],
				'city_ar' => $data['org_city_ar'],
				'address' => $data['org_address'],
				'address_ar' => $data['org_address_ar'],
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
				'user_id' => $user->id
			]);
			
			
			$organization->fill(['supervisor_id' => $user->id]);
			
			$organization->save();
			
			$organization->fresh()->initData($manager);
			
			return $manager;
		}
		
	}
