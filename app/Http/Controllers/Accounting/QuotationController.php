<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Quotation\CreateQuotationRequest;
	use App\Invoice;
	use App\Item;
	use App\Manager;
	use App\User;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class QuotationController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			$clients = User::where('is_client',true)->get();
			$creators = Manager::all();
			return view('accounting.quotations.index',compact('clients','creators'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$salesmen = Manager::all();
			$clients = User::where('is_client',true)->get()->toArray();
			$expenses = Item::where('is_expense',true)->get();
			$gateways = auth()->user()->gateways()->get();
//			Account::where([['slug','temp_reseller_account'],['is_system_account',true]])->get()
			return view('accounting.quotations.create',compact('clients','salesmen','gateways','expenses'));
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateQuotationRequest $request)
		{
			
			return $request->save();
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Invoice $invoice
		 *
		 * @return Response
		 */
		public function edit(Invoice $quotation)
		{
			
			
			$salesmen = Manager::all();
			$clients = User::where('is_client',true)->get()->toArray();
			$expenses = Item::where('is_expense',true)->get();
			$gateways = auth()->user()->gateways()->get();
			

			$quotation = $quotation->load('items.item.items.item','items.item.data','sale.client','sale.salesman');
//			Account::where([['slug','temp_reseller_account'],['is_system_account',true]])->get()
			return view('accounting.quotations.to_sale',compact('clients','salesmen','gateways','expenses','quotation'));
			
			//
		}
		
	}