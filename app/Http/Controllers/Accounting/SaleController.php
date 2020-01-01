<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Sale\CreateSaleRequest;
	use App\Http\Requests\Accounting\Sale\DatatableRequest;
	use App\Http\Requests\Accounting\Sale\ReturnSaleRequest;
	use App\Http\Requests\CreateQuotationRequest;
	use App\Http\Requests\CreateReturnSaleRequest;
	use App\Http\Requests\CreateSalesInvoiceRequest;
	use App\Invoice;
	use App\Item;
	use App\Manager;
	use App\SaleInvoice;
	use App\Scopes\QuotationScope;
	use App\User;
	use Illuminate\Contracts\Routing\ResponseFactory;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Response;
	use Illuminate\View\View;
	
	
	class SaleController extends Controller
	{
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
			$clients = User::where('is_client',true)->get();
			$creators = Manager::all();
			return view('accounting.sales.index',compact('clients','creators'));
			
		}
		
		/**
		 * @param DatatableRequest $request
		 *
		 * @return mixed
		 */
		public function datatable(DatatableRequest $request)
		{
			return $request->data();
		}
		
		/**
		 * @return Factory|View
		 */
		public function create()
		{
			
			$salesmen = Manager::all();
			$clients = User::where('is_client',true)->get()->toArray();
			$expenses = Item::where('is_expense',true)->get();
			$gateways = [];
			
			foreach (auth()->user()->gateways->load('gateway') as $gateway){
				$gateways[] = $gateway['gateway'];
			}
			return view('accounting.sales.create',compact('clients','salesmen','gateways','expenses'));
		}
		
		/**
		 * @param CreateSaleRequest $request
		 *
		 * @return ResponseFactory|Response
		 */
		public function store(CreateSaleRequest $request)
		{
			
			
			return $request->save();
			
		}
		
		/**
		 * @param Invoice $sale
		 *
		 * @return Factory|View
		 */
		public function show(Invoice $sale)
		{
			$transactions = $sale->transactions()->where('description','!=','client_balance')->get();
			$invoice = $sale;
			return view('accounting.sales.show',compact('invoice','transactions'));
			//
		}
		
		/**
		 * @param Invoice $sale
		 *
		 * @return Factory|View
		 */
		public function edit(Invoice $sale)
		{
			$invoice = $sale;
			$sale = $invoice->sale;
			$items = [];
			$data_source_items = $invoice->items()->with('item')->get();
			foreach ($data_source_items as $item){
				if ($item->item->is_need_serial){
					$item['serials'] = $item->item->serials()->sale($invoice->id)->get();
				}
				$items [] = $item;
			}
			
//			return $items;
			$expenses = Item::where('is_expense',true)->get();
			$gateways = Account::whereIn('id',auth()->user()->gateways()->pluck('gateway_id')->toArray())->get();
			return view('accounting.sales.edit',compact('sale','invoice','items','gateways','expenses'));
		}
		
		/**
		 * @param $invoice_id
		 *
		 * @return Factory|View
		 */
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
		}
		
		/**
		 * @param CreateReturnSaleRequest $request
		 * @param SaleInvoice $sale
		 *
		 * @return ResponseFactory|Response
		 */
		public function update(Invoice $sale,ReturnSaleRequest $request)
		{
//			return  $sale;
			return $request->makeReturn($sale);
			
		}
		
		/**
		 * @return Factory|View
		 */
		public function quotations()
		{
			$sales = SaleInvoice::withoutGlobalScope(QuotationScope::class)->where('invoice_type','quotation')->with
			('invoice')->orderBy
			('id','desc')
				->paginate(20);
			return view('sales.quotations',compact('sales'));
			
		}
		
		/**
		 * @return Factory|View
		 */
		public function quotation_create()
		{
			$salesmen = User::where('is_manager',true)->get()->toArray();
			$clients = User::where('is_client',true)->get()->toArray();
			
			
			return view('sales.quotation_create',compact('salesmen','clients'));
			
		}
		
		/**
		 * @param $quotation_id
		 *
		 * @return Factory|View
		 */
		public function view_quotation($quotation_id)
		{
			$sale = SaleInvoice::withoutGlobalScope(QuotationScope::class)->findOrFail($quotation_id);
			$transactions = $sale->invoice->transactions()->where('description','!=','client_balance')->get();
			
			
			return view('sales.quotation_show',compact('sale','transactions'));
			
		}
		
		/**
		 * @param CreateQuotationRequest $request
		 *
		 * @return array
		 */
		public function quotation_store(CreateQuotationRequest $request)
		{
			return $request->save();
		}
		
	}
