<?php
	
	namespace App\Http\Controllers;
	
	use App\Models\Account;
	use App\Models\Department;
	use App\Models\Invoice;
	use App\Models\Item;
	use App\Models\Manager;
	use App\Models\User;
	
	class SaleController extends Controller
	{
		//
		/**
		 * Display a listing of the resource.
		 * @return Response
		 */
		public function index()
		{
			// auth()->loginUsingId(1);
			$clients = User::where('is_client', true)->get();
			
			$creators = Manager::get();
			$departments = Department::get();

//			return  $departments;
			
			
			return view('sales.index', compact('clients', 'creators', 'departments'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 * @return Response
		 */
		public function create()
		{
			$salesmen = Manager::all();
			$clients = User::where('is_client', true)->get()->toArray();
			$expenses = Item::where('is_expense', true)->get();
			$gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
			
			return view('sales.create', compact('clients', 'salesmen', 'gateways', 'expenses'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 * @return Response
		 */
		public function drafts()
		{
			$clients = User::where('is_client', true)->get();
			$creators = Manager::all();
			return view('sales.drafts', compact('clients', 'creators'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 * @return Response
		 */
		public function createDraft()
		{
			$salesmen = Manager::all();
			$clients = User::where('is_client', true)->get()->toArray();
			$expenses = Item::where('is_expense', true)->get();
			
			$gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
			return view('sales.create_draft', compact('clients', 'salesmen', 'gateways', 'expenses'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 * @return Response
		 */
		public function createServiceDraft()
		{
			$salesmen = Manager::all();
			$clients = User::where('is_client', true)->get()->toArray();
			$services = Item::where('is_service', true)->get();
			$gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
			return view('sales.create_draft_service', compact('clients', 'salesmen', 'gateways', 'services'));
		}
		
		function clone(Invoice $sale)
		{
			
			$salesmen = Manager::all();
			$clients = User::where('is_client', true)->get()->toArray();
			$expenses = Item::where('is_expense', true)->get();
			$gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
			$sale = $sale->load('items.item.items.item', 'items.item.data', 'sale.client', 'sale.salesman');
			return view('sales.clone_draft', compact('clients', 'salesmen', 'gateways', 'expenses', 'sale'));
		}
		
		
		function toInvoice(Invoice $sale)
		{
			
			$salesmen = Manager::all();
			$clients = User::where('is_client', true)->get()->toArray();
			$expenses = Item::where('is_expense', true)->get();
			$gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
			$sale = $sale->load('items.item.items.item', 'items.item.data', 'sale.client', 'sale.salesman');
			return view('sales.clone', compact('clients', 'salesmen', 'gateways', 'expenses', 'sale'));
		}
		
		/**
		 * Show the specified resource.
		 * @param Invoice $sale
		 * @return Response
		 */
		public function show(Invoice $sale)
		{
//        $sale = $sale->withoutGlobalScope('draft');
			
			$transactions = $sale->transactions()->get();
			$invoice = $sale;
			$invoice->sale = $invoice->sale()->withoutGlobalScope('draft')->first();
			
			return view('sales.view', compact('invoice', 'transactions'));
		}
		
		/**
		 * Show the form for editing the specified resource.
		 * @param Invoice $sale
		 * @return Response
		 */
		public function edit(Invoice $sale)
		{
			$invoice = $sale;
			$sale = $invoice->sale;
			$items = [];
			$data_source_items = $invoice->items()->where('parent_kit_id', 0)->with('item')->get();
			
			foreach($data_source_items as $item) {
				if($item->item->is_need_serial) {
					$item['serials'] = $item->item->serials()->sale($invoice->id)->get();
				}
				
				if($item->item->is_kit) {
					$item['items'] = $invoice->items()->kitItems($item->id)->with('item')->get();
				}
				
				
				$items[] = $item;
			}
			
			// return $items;
			$expenses = [];//Item::where('is_expense', true)->get()
			$gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
			return view('sales.create_return', compact('sale', 'invoice', 'items', 'gateways', 'expenses'));
		}
		
	}
