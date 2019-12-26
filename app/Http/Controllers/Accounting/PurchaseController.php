<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Expense;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Purchase\CreatePurchaseRequest;
	use App\Http\Requests\Accounting\Purchase\DatatableRequest;
	use App\Http\Requests\CreateReturnPurchaseRequest;
	use App\Invoice;
	use App\PurchaseInvoice;
	use App\User;
	use Exception;
	use Illuminate\Http\Response;
	
	class PurchaseController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			return view('accounting.purchases.index');
			//
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
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$receivers = User::where('is_manager',true)->get()->toArray();
			$vendors = User::where([['is_vendor',true]])->get()->toArray();//,['is_system_user',false]
			$expenses = Expense::where('appear_in_purchase',true)->get();
			//auth()->user()->gateways()->pluck('gateway_id')->toArray()
			$gateways = Account::where('slug','gateway')->take(2)->get();
			return view('accounting.purchases.create',compact('vendors','receivers','gateways','expenses'));
			//
		}
		
		/**
		 * @param CreatePurchaseRequest $request
		 *
		 * @return array
		 * @throws Exception
		 */
		public function store(CreatePurchaseRequest $request)
		{
			return $request->save();
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Invoice $purchase
		 *
		 * @return Response
		 */
		public function show(Invoice $purchase)
		{
			
			$transactions = $purchase->transactions()->where('description','!=','vendor_balance')->get();
			$invoice = $purchase;
			return view('accounting.purchases.show',compact('invoice','transactions'));
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param PurchaseInvoice $purchaseInvoice
		 *
		 * @return Response
		 */
		public function edit(Invoice $purchase)
		{
			
			$invoice = $purchase->invoice;
			// items
			$items = [];
			foreach ($purchase->invoice->items()->with('item')->get() as $item){
				if ($item->item->is_need_serial){
					$item['serials'] = $item->item->serials()->purchase($invoice->id)->get();
				}
				$items [] = $item;
			}
			
			
			$gateways = Account::whereIn('id',auth()->user()->gateways()->pluck('gateway_id')->toArray())->get();
			return view('purchases.edit',compact('purchase','invoice','items','gateways'));
			//
		}
		
		/**
		 * @param CreateReturnPurchaseRequest $request
		 * @param PurchaseInvoice $purchase
		 *
		 * @throws Exception
		 */
		public function update(CreateReturnPurchaseRequest $request,PurchaseInvoice $purchase)
		{
			return $request->save($purchase);
			//
		}
		
	}


