<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Accounting\ChartAccounting;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Account\CreateAccountRequest;
	use App\Http\Requests\Accounting\Account\UpdateAccountRequest;
	use App\Item;
	use App\User;
	use Exception;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\View\View;
	
	class ChartsController extends Controller
	{
		use ChartAccounting;
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			$accounts = Account::where('parent_id',0)->get();
			return view('accounting.charts.index',compact('accounts'));
			//
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create(Request $request)
		{
			
			
			$this->middleware(['permission:create category']);
			$request->validate([
				'parent_id' => 'nullable|exists:accounts,id|integer'
			]);
			if (isset($request->parent_id)){
				$parent_id = $request->parent_id;
			}else{
				$parent_id = 0;
			}
			
			$accounts = Account::get();
			
			return view('accounting.charts.create',compact('accounts','parent_id'));
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param CreateAccountRequest $request
		 *
		 * @return Response
		 */
		public function store(CreateAccountRequest $request)
		{
			$request->save();
			return redirect(route('accounting.accounts.index'));
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Account $account
		 *
		 * @return Response
		 */
		public function show(Account $account)
		{
			
			if ($account->slug == 'clients'){
				$users = User::where('is_client',true)->paginate(50);//$this->get_users_transactions
				//('client_balance')
				
				return view('accounting.charts.transactions.identity',compact('users','account'));
			}else if ($account->slug == 'vendors'){
				
				$users = User::where('is_vendor',true)->paginate(50);
//				$users = $this->get_users_transactions('vendor_balance');
				return view('accounting.charts.transactions.identity',compact('users','account'));
			}else if ($account->slug == 'stock'){
				$items = $this->get_account_stock_item_transactions();
				$items = $items['items'];
				return view('accounting.charts.transactions.items',compact('items','account'));
				
			}
			
			
			$transactions = $this->load_account_transactions($account);

//			return $account;
//			return  $transactions;
			return view('accounting.charts.transactions.list',compact('account','transactions'));
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Account $account
		 *
		 * @return Response
		 */
		public function edit(Account $account)
		{
			
			
			$ids = $account->children()->pluck('id')->toArray();
			$ids[] = $account->id;
//			return $ids;
			$accounts = Account::WhereNotIn('id',$ids)->get();
			return view('accounting.charts.edit',compact('account','accounts'));
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param UpdateAccountRequest $request
		 * @param Account $account
		 *
		 * @return Response
		 */
		public function update(UpdateAccountRequest $request,Account $account)
		{
			
			$request->update($account);
			
			return redirect(route('accounting.accounts.index'));
		}
		
		/**
		 * @param Account $account
		 *
		 * @throws Exception
		 */
		public function destroy(Account $account)
		{
			$account->delete();
		}
		
		/**
		 * @param Item $item
		 * @param Account $account
		 *
		 * @return Factory|View
		 */
		public function item(Item $item,Account $account)
		{
			
			$transactions = $this->load_item_transactions($item,$account);
			return view('accounting.charts.transactions.item',compact('item','transactions','account'));
			//
		}
		
		/**
		 * @param User $client
		 * @param Account $account
		 *
		 * @return Factory|View
		 */
		public function client(User $client,Account $account)
		{
			
			$transactions = $this->load_client_transactions($account,$client->id);
			return view('accounting.charts.transactions.client',compact('client','transactions','account'));
			
		}
		
		/**
		 * @param User $vendor
		 * @param Account $account
		 *
		 * @return Factory|View
		 */
		public function vendor(User $vendor,Account $account)
		{
			
			$transactions = $this->load_vendor_transactions($account,$vendor->id);
			return view('accounting.charts.transactions.vendor',compact('vendor','transactions','account'));
			
		}
		
	}
	
