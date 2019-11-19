<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Http\Controllers\ControllersHelper\ChartControllerClientsHelper;
	use App\Http\Controllers\ControllersHelper\ChartControllerGatewayHelper;
	use App\Http\Controllers\ControllersHelper\ChartControllerStockHelper;
	use App\Http\Requests\CreateAccountRequest;
	use App\Http\Requests\UpdateAccountRequest;
	use App\Item;
	use App\User;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class AccountsController extends Controller
	{
		
		use ChartControllerGatewayHelper,ChartControllerStockHelper,ChartControllerClientsHelper;
		
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
			
			if($request->has('is_gateway') && $request->filled('is_gateway'))
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
			$view = [];
			
			
			if ($account->slug == 'gateway')
				$view = $this->chart_gateway_view($account);
			
			
			if ($account->slug == 'stock')
				$view = $this->chart_stock_view($account);
			
			
			if ($account->slug == 'clients')
				$view = $this->chart_clients_view($account);
			
			
			return $view;
			//
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
			if($request->has('is_gateway') && $request->filled('is_gateway'))
				$data['is_gateway'] = true;
			else
				$data['is_gateway'] = false;
			
			$account->update($data);
			
			return redirect(route('management.accounts.index'));
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function delete(Account $account)
		{
			
			$account->delete();
			return redirect(route('management.accounts.index'));
			//
		}
		
		public function item(Item $item,Chart $chart)
		{
			
			
			$activities = $this->get_single_item_history_depend_on_current_chart($item,$chart);
			
			return view('accounts.single_item_histories',compact('item','activities','chart'));
			//
		}
		
		public function client(User $client,Chart $chart)
		{
			$activities = $client->to_client_invoices_history();//['data']
			
			return $activities;
			
			$activities = $activities['data'];
			return view('accounts.client_history',compact('client','activities','chart'));
			
		}
	}
	
