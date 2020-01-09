<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Expense;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Purchase\CreatePurchaseRequest;
	use App\Http\Requests\Accounting\Purchase\DatatableRequest;
	use App\Http\Requests\Accounting\Purchase\ReturnPurchaseRequest;
	use App\Http\Requests\CreateReturnPurchaseRequest;
	use App\Invoice;
	use App\Manager;
	use App\PurchaseInvoice;
	use App\User;
	use Exception;
	use Illuminate\Contracts\Routing\ResponseFactory;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Response;
	use Illuminate\View\View;
	
	class PurchaseController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			$vendors = User::where('is_vendor',true)->get();
			$creators = Manager::all();
			return view('accounting.purchases.index',compact('vendors','creators'));
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
			$receivers = Manager::all();
			$vendors = User::where([['is_vendor',true],['is_system_user',false]])->get()->toArray();//,['is_system_user',false]
			$expenses = Expense::all();
			$gateways = auth()->user()->gateways()->get();
//			$gateways = Account::where([['slug','temp_reseller_account'],['is_system_account',true]])->get();
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
		 * @param Invoice $purchase
		 *
		 * @return Factory|View
		 */
		public function edit(Invoice $purchase)
		{
			
			$invoice = $purchase;
			$purchase = $invoice->purchase;
			$items = [];
			$data_source_items = $invoice->items()->with('item')->get();
			foreach ($data_source_items as $item){
				if ($item->item->is_need_serial){
					$item['serials'] = $item->item->serials()->purchase($invoice->id)->get();
				}
				$items [] = $item;
			}
			$gateways = auth()->user()->gateways()->get();
//			$gateways = Account::where([['slug','temp_reseller_account'],['is_system_account',true]])->get();
			
			return view('accounting.purchases.edit',compact('purchase','invoice','items','gateways'));
		}
		
		/**
		 * @param CreateReturnPurchaseRequest $request
		 * @param PurchaseInvoice $purchase
		 *
		 * @return ResponseFactory|Response|mixed
		 * @throws Exception
		 */
		public function update(ReturnPurchaseRequest $request,Invoice $purchase)
		{
			return $request->makeReturn($purchase);
		}
		
	}


