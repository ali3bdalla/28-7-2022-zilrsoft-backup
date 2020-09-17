<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Inventory\ActivateAdjustStockRequest;
	use App\Http\Requests\Accounting\Inventory\AdjustStockDatatableRequest;
	use App\Http\Requests\Accounting\Inventory\CreateAdjustStockRequest;
	use App\Models\Invoice;
	use App\Models\User;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class AdjustStockController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			return view('accounting.inventories.adjust_stock.index');
			//
		}
		
		public function inventory_reconciliation()
		{
			return view('accounting.inventories.adjust_stock.inventory_reconciliation');
		}
		
		public function datatable(AdjustStockDatatableRequest $request)
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
			$user = User::where([
				['user_slug','beginning-inventory'],
				['is_system_user',true]
			])->first();
			
			$creator = auth()->user()->with('department','branch')->first();
			return view('accounting.inventories.adjust_stock.create',compact('user','creator'));
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateAdjustStockRequest $request)
		{
			return $request->save();
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Invoice $invoice
		 *
		 * @return Response
		 */
		public function show(Invoice $adjust_stock)
		{
//			return $invoice->items;
			
			$invoice = $adjust_stock;
			$transactions = $adjust_stock->transactions()->get();
//			return  $transactions;
			return view('accounting.inventories.adjust_stock.show',compact('invoice','transactions'));
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Invoice $adjust_stock
		 * @param ActivateAdjustStockRequest $request
		 *
		 * @return RedirectResponse
		 */
		public function edit(Invoice $adjust_stock,ActivateAdjustStockRequest $request)
		{
			return $request->activate($adjust_stock);
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Invoice $invoice
		 *
		 * @return Response
		 */
		public function update(Request $request,Invoice $invoice)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param Invoice $invoice
		 *
		 * @return Response
		 */
		public function destroy(Invoice $invoice)
		{
			//
		}
	}
