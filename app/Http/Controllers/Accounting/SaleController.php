<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Sale\CreateSaleRequest;
	use App\Http\Requests\Accounting\Sale\DatatableRequest;
	use App\Http\Requests\CreateQuotationRequest;
	use App\Http\Requests\CreateReturnSaleRequest;
	use App\Http\Requests\CreateSalesInvoiceRequest;
	use App\Invoice;
	use App\Item;
	use App\Manager;
	use App\SaleInvoice;
	use App\Scopes\QuotationScope;
	use App\User;
	
	
	class SaleController extends Controller
	{
		
		public function index()
		{
			$clients = User::where('is_client',true)->get();
			$creators = Manager::all();
			return view('accounting.sales.index',compact('clients','creators'));
			
		}
		
		public function datatable(DatatableRequest $request)
		{
			return $request->data();
		}
		
		public function create()
		{
			
			$salesmen = Manager::all();
			$clients = User::where('is_client',true)->get()->toArray();
			$expenses = Item::where('is_expense',true)->get();
			$gateways = Account::whereIn('id',auth()->user()->gateways()->pluck('gateway_id')->toArray())
				->get();//'id',auth()->user()->gateways()->pluck('gateway_id')->toArray()
			return view('accounting.sales.create',compact('clients','salesmen','gateways','expenses'));
		}
		
		public function store(CreateSaleRequest $request)
		{
			
			
			return $request->save();
			
		}
		
		public function show(Invoice $sale)
		{
			$transactions = $sale->transactions()->where('description','!=','client_balance')->get();
			$invoice = $sale;
			return view('accounting.sales.show',compact('invoice','transactions'));
			//
		}
		
		public function edit(Invoice $sale)
		{
			$invoice = $sale->invoice;
			// items
			$items = [];
			$data_source_items = $sale->invoice->items()->with('item')->get();
			foreach ($data_source_items as $item){
				if ($item->item->is_need_serial){
					$item['serials'] = $item->item->serials()->sale($invoice->id)->get();
				}
				$items [] = $item;
			}
			
			$expenses = Item::where('is_expense',true)->get();
			
			$gateways = Account::whereIn('id',auth()->user()->gateways()->pluck('gateway_id')->toArray())->get();
			return view('sales.edit',compact('sale','invoice','items','gateways','expenses'));
		}
		
		public function clone($invoice_id)
		{
			
			// return 1;
			$invoice = SaleInvoice::withoutGlobalScope(QuotationScope::class)->findOrFail($invoice_id);
			
			// return $invoice;
			
			$data['subinvoice'] = $invoice;
			$data['invoice'] = $invoice->invoice;
			$items = $invoice->invoice->items;
			// $items =
			
			$salesmen = User::where('is_manager',true)->get()->toArray();
			$clients = User::where('is_client',true)->get()->toArray();
			
			$expenses = Item::where('is_expense',true)->get();
			
			$gateways = Account::whereIn('id',auth()->user()->gateways()->pluck('gateway_id')->toArray())->get();
			
			
			$is_clone = true;
			return view('sales.create',compact('clients','salesmen','gateways','expenses','items','is_clone'));


//			return view('sales.create',compact('items'));
		}
		
		public function update(CreateReturnSaleRequest $request,SaleInvoice $sale)
		{
			return $request->save($sale);
			
		}
		
		public function unpaid(User $user)
		{
			return SaleInvoice::where('client_id',$user->id)->with('invoice.creator','client')->whereHas('invoice',
				function ($query){
					return $query->whereIn('current_status',['credit']);
				})->get();
			
			//return SaleInvoice::where('client_id',$user->id)->with('invoice.creator','client')->get();
		}
		
		public function unpaid_all()
		{
			return SaleInvoice::with('invoice.creator','client')->whereHas('invoice',function ($query){
				return $query->whereIn('current_status',['credit']);
			})->get();
		}
		
		public function quotations()
		{
			$sales = SaleInvoice::withoutGlobalScope(QuotationScope::class)->where('invoice_type','quotation')->with
			('invoice')->orderBy
			('id','desc')
				->paginate(20);
//			return $sales;
			return view('sales.quotations',compact('sales'));
			
		}
		
		public function quotation_create()
		{
			$salesmen = User::where('is_manager',true)->get()->toArray();
			$clients = User::where('is_client',true)->get()->toArray();
			
			
			return view('sales.quotation_create',compact('salesmen','clients'));
			
		}
		
		public function view_quotation($quotation_id)
		{
			$sale = SaleInvoice::withoutGlobalScope(QuotationScope::class)->findOrFail($quotation_id);
//			return $sale;
			$transactions = $sale->invoice->transactions()->where('description','!=','client_balance')->get();
			
			
			return view('sales.quotation_show',compact('sale','transactions'));
			
		}
		
		public function quotation_store(CreateQuotationRequest $request)
		{
			
			
			return $request->save();
			
			
			//
		}
		
	}
