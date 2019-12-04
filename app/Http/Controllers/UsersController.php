<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Bank;
	use App\Branch;
	use App\CountryBank;
	use App\Gateway;
	use App\Http\Requests\CreateMethodsAccountRequest;
	use App\Http\Requests\CreateUserForm;
	use App\User;
	use Auth;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class UsersController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			$users = User::paginate(20);
			// $users->withPath('custom/url');
			return view('users.index',compact('users'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			//
			
			$branchs = Branch::with('departments')->get();
			$gateways = Account::where('is_gateway',true)->get();
			$banks = Bank::all();
			return view('users.create',compact('branchs','gateways','banks'));
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateUserForm $request)
		{
			
			if ($request->is_manager){
				$request->validateManger();
			}
			
			
			return $request->save();
			
			
			//  return redirect(route('management.users.index'));
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param User $user
		 *
		 * @return Response
		 */
		public function show(User $user)
		{
			//
			return view('users.show',compact('user'));
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param User $user
		 *
		 * @return Response
		 */
		public function edit(User $user)
		{
			return view('users.edit',compact('user'));
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param User $user
		 *
		 * @return Response
		 */
		public function update(Request $request,User $user)
		{
		
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param User $user
		 *
		 * @return Response
		 */
		public function update_payments_accounts(User $user)
		{

//    	     $accounts = $user->accounts;
			
			$accounts = $user->accounts()->with('gateway','bank')->get()->groupBy('gateway_id');
			
			
			return view('users.accounts',compact('user','accounts'));
		}
		
		public function create_payments_accounts(User $user)
		{
			
			$accounts = $user->accounts()->with('gateway','bank')->get()->groupBy('gateway_id');
			
			
			$organization_gateways = auth()->user()->organization->gateways()->whereIn('gateways.id',[
				2,4,6
			])->with('fields')
				->get();
			//>where
			//('gateways.is_has_fields',true)
			$banks = CountryBank::all();
			
			return view('users.accounts_create',compact('user','accounts','organization_gateways','banks'));
		}
		
		public function store_payments_accounts(User $user,CreateMethodsAccountRequest $request)
		{
			$data = $request->except('user_id');
			$data['organization_id'] = auth()->user()->organization_id;
			$user->accounts()->create($data);
			return route('management.users.update_payments_accounts',$user->id);
		}
		//
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param User $user
		 *
		 * @return Response
		 */
		public function destroy(User $user)
		{
			//
			
			$user->delete();
			
			return back();
		}
		
		/**
		 * sign out the users
		 *
		 * @param User $user
		 *
		 * @return Response
		 */
		public function signout()
		{
			//
			
			Auth::logout();
			
			return back();
		}
		
		public function get_ways_with_accounts_that_user_has_account_on_them(User $user,Gateway $payWay)
		{
			
			
			$result = $payWay;
			$result['children'] = $children = $payWay->children()->with(['accounts' => function ($query) use ($user){
				return $query->whereIn('id',$user->accounts()->pluck('id'));
			}])->whereHas('accounts',function ($query2) use ($user){
				return $query2->whereIn('id',$user->accounts()->pluck('id'));
			})->get();
			
			$result['accounts'] = $payWay->accounts()->whereIn('id',$user->accounts()->pluck('id'))->get();
			
			
			return $result;
//        ['children' => function($query) use ($user) {
//
////            return $query->with(['accounts'=> function($query2) use ($user) {
////                return $query2->whereIn('id',$user->accounts()->pluck('id'));
////            }])->whereHas('accounts',function($query2) use ($user) {
////                return $query2->whereIn('id',$user->accounts()->pluck('id'));
////            });
//
//        },'accounts' => function($query2) use ($user) {
//            return $query2->whereIn('id',$user->accounts()->pluck('id'));
//        }]
		
		}
		
	}
