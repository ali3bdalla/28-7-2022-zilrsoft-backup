<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Http\Requests\CreateReturnSaleRequest;
	use App\Http\Requests\CreateSalesInvoiceRequest;
	use App\Item;
	use App\SaleInvoice;
	use App\User;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	
	
	class SaleController extends Controller
	{
		
		public function index()
		{
			$sales = SaleInvoice::with('invoice')->orderBy('id','desc')->paginate(20);
			return view('sales.index',compact('sales'));
			
		}
		
		public function table(Request $request)
		{
			$query = SaleInvoice::with('invoice.creator','client');
			
			
			if ($request->has('creators') && $request->filled('creators')){
				$query = $query->whereIn('creator_id',$request->creators);
			}
			
			
			if ($request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
				$request->filled
				('endDate')){
				
				$_startDate = new Carbon($request->startDate);
				$_endDate = new Carbon($request->endDate);
				$query = $query->whereBetween('created_at',[
					$_startDate->toDateTimeString(),
					$_endDate->toDateTimeString()
				]);
			}
			
			
			if ($request->has('current_status') && $request->filled('current_status') && in_array
				($request->current_status,['paid','credit'])){
				$query = $query->where('current_status',$request->loadType);
				
			}
			
			
			if ($request->has('issued_status') && $request->filled('issued_status') && in_array($request->issued_status,['paid','credit'])){
				$query = $query->where('issued_status',$request->loadType);
				
			}
			
			
			return $query->orderBy('id','desc')->paginate(20);
			
			
		}
		
		public function create()
		{
			
			
			$salesmen = User::where('is_manager',true)->get()->toArray();
			$clients = User::where('is_client',true)->get()->toArray();
			
			$expenses = Item::where('is_expense',true)->get();
			
			$gateways = Account::whereIn('id',auth()->user()->gateways()->pluck('gateway_id')->toArray())->get();
			
			
			return view('sales.create',compact('clients','salesmen','gateways','expenses'));
		}
		
		public function store(CreateSalesInvoiceRequest $request)
		{
			
			
			return $request->save();
			
			
			//
		}
		
		public function show(SaleInvoice $sale)
		{
			
			$transactions = $sale->invoice->transactions()->where('description','!=','client_balance')->get();
			
			
			return view('sales.show',compact('sale','transactions'));
			//
		}
		
		public function edit(SaleInvoice $sale)
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
		
		public function clone(SaleInvoice $invoice)
		{
			
			$data['subinvoice'] = $invoice;
			$data['invoice'] = $invoice->invoice;
//			$data['items'] = $invoice->invoice()->items;
			
			return $data;
		}
		
		public function update(CreateReturnSaleRequest $request,SaleInvoice $sale)
		{
			return $request->save($sale);
			
		}
		
		public function destroy(SaleInvoice $sale)
		{
		
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
		
	}
