<?php
	
	namespace App\Http\Controllers\App\API;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Sales\FetchSalesRequest;
	use App\Http\Requests\Sales\StoreDraftSaleRequest;
	use App\Http\Requests\Sales\StoreReturnSaleRequest;
	use App\Http\Requests\Sales\StoreSaleRequest;
	use App\Models\Invoice;
	
	class SaleController extends Controller
	{
		//
		
		public function index(FetchSalesRequest $request)
		{
			return $request->getData();
		}
		
		
		public function store(StoreSaleRequest $request)
		{
			return $request->store();
		}
		
		public function storeDraft(StoreDraftSaleRequest $request)
		{
			return $request->store();
		}
		
		
		public function storeReturnSale(Invoice $sale, StoreReturnSaleRequest $request)
		{
			return $request->store($sale);
		}
		
		public function show(Invoice $purchase)
		{
			return $purchase;
		}
	}
