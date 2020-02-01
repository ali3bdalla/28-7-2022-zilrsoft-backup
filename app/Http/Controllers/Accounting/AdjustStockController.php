<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Inventory\AdjustStockDatatableRequest;
	use App\Http\Requests\Accounting\Inventory\CreateAdjustStockRequest;
	use App\Invoice;
	use App\User;
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
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Invoice $invoice
		 *
		 * @return Response
		 */
		public function show(Invoice $invoice)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Invoice $invoice
		 *
		 * @return Response
		 */
		public function edit(Invoice $invoice)
		{
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
