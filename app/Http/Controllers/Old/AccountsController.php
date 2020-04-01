<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Http\Controllers\ControllersHelper\AccountGeneralHelper;
	use App\Http\Requests\CreateAccountRequest;
	use App\Http\Requests\UpdateAccountRequest;
	use App\Item;
	use App\User;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class AccountsController extends Controller
	{
		
		use AccountGeneralHelper;
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			$accounts = Account::where('parent_id',0)->with(
				'children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children'
			)->get();
			
			
			return view('accounts.index',compact('accounts'));
			//
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			
			
			$accounts = Account::get();
			
			
			$isClone = false;
			return view('accounts.create',compact('accounts','isClone'));
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateAccountRequest $request)
		{
			$account = Account::find($request->parent_id);
			
			$data = $request->only('parent_id','name','ar_name');
			$data['organization_id'] = auth()->user()->organization_id;
			$data['serial'] = auth()->user()->organization_id;
			$data['slug'] = $account->slug;
			
			if ($request->has('is_gateway') && $request->filled('is_gateway'))
				$data['is_gateway'] = true;
			else
				$data['is_gateway'] = false;
			
			
			auth()->user()->accounts()->create($data);
			return redirect(route('management.accounts.index'));
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function show(Account $account)
		{
			
			if ($account->slug == 'clients'){
//				$users = User::where('is_client',true)->get();
				$users = $this->get_users_transactions('client_balance');
//				return  $users;
				return view('accounts.users',compact('users','account'));
			}else if ($account->slug == 'vendors'){
//				$users = User::where('is_vendor',true)->get();
				$users = $this->get_users_transactions('vendor_balance');
//				return  $users;
				return view('accounts.users',compact('users','account'));
			}else if ($account->slug == 'stock'){
				$items = $this->get_account_stock_item_transactions();
				$items = $items['items'];
//				$items = collect($items)->sortByDesc('total_debit');
				return view('accounts.items',compact('items','account'));
				
			}
			
			
			$transactions = $this->load_account_transactions($account);
			
			
			return view('accounts.transactions',compact('account','transactions'));
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function edit(Account $account)
		{
			
			$ids = $account->children()->pluck('id')->toArray();
			$ids[] = $account->id;

//			return $ids;
			$accounts = Account::WhereNotIn('id',$ids)->get();
			return view('accounts.edit',compact('account','accounts'));
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function update(UpdateAccountRequest $request,Account $account)
		{
			
			$data = $request->only('parent_id','name','ar_name');
			if ($request->has('is_gateway') && $request->filled('is_gateway'))
				$data['is_gateway'] = true;
			else
				$data['is_gateway'] = false;
			
			$account->update($data);
			
			return redirect(route('management.accounts.index'));
		}
		
	
		public function delete(Account $account)
		{
			
			$account->delete();
			return redirect(route('management.accounts.index'));
			//
		}
		
		public function item(Item $item,Account $account)
		{

//			return $account;
			
			$transactions = $this->load_item_transactions($item,$account);
			
			return view('accounts.item',compact('item','transactions','account'));
			//
		}
		
		public function client(User $client,Account $account)
		{
			
			$transactions = $this->load_client_transactions($account,$client->id);

//			return $transactions;

//			$transactions =
//			return $transactions;
//			$activities = $activities['data'];
			return view('accounts.client',compact('client','transactions','account'));
			
		}
		
		public function vendor(User $vendor,Account $account)
		{
			
			$transactions = $this->load_vendor_transactions($account,$vendor->id);


//			return $transactions;

//			$transactions =
//			return $transactions;
//			$activities = $activities['data'];
			return view('accounts.vendor',compact('vendor','transactions','account'));
			
		}
		
	}
	
