<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Invoice;
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
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			return view('accounting.inventories.adjust_stock.create');
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(Request $request)
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
