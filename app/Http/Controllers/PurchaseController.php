<?php
	
	namespace App\Http\Controllers;
	
	use App\Models\Expense;
	use App\Models\Invoice;
	use App\Models\Manager;
	use App\Models\User;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	
	class PurchaseController extends Controller
	{
		
		
		public function index()
		{
			$vendors = User::where('is_vendor', true)->get();
			$creators = Manager::all();
			$is_pending = false;
			return view('accounting.purchases.index', compact('vendors', 'creators', 'is_pending'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$receivers = Manager::all();
			$vendors = User::where([['is_vendor', true], ['is_system_user', false]])->get()->toArray();
			$expenses = Expense::all();
			$gateways = [];
			return view('accounting.purchases.create', compact('vendors', 'receivers', 'gateways', 'expenses'));
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
			foreach($data_source_items as $item) {
				if($item->item->is_need_serial) {
					$item['serials'] = $item->item->serials()->purchase($invoice->id)->get();
				}
				$items [] = $item;
			}
			$gateways = [];
			
			
			return view('accounting.purchases.edit', compact('purchase', 'invoice', 'items', 'gateways'));
		}
		
		public function clone(Invoice $purchase)
		{
			$receivers = Manager::all();
			$vendors = User::where([['is_vendor', true], ['is_system_user', false]])->get()->toArray();
			$expenses = Expense::all();
			$gateways = [];
			
			$cloned_items = [];
			foreach($purchase->items()->with('item')->get() as $item) {
				if($item->item->is_need_serial) {
					$item->serials = $item->item->serials()->withoutGlobalScope("draft")
						->where([["purchase_id", $purchase->id]])
						->pluck('serial');
				} else {
					$item->serials = [];
				}
				$cloned_items[] = $item;
			}
			return view('accounting.purchases.clone', compact('vendors', 'receivers', 'gateways', 'expenses', 'purchase', 'cloned_items'));
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
			$transactions = $purchase->transactions()->get();
			
			
			return view(
				'accounting.purchases.show', [
				'invoice' => $purchase, 'transactions' => $transactions
			]
			);
		}
		
		
		public function drafts()
		{
			$vendors = User::where('is_vendor', true)->get();
			$creators = Manager::all();
			$is_pending = true;
			return view('accounting.purchases.index', compact('vendors', 'creators', 'is_pending'));
		}
		
	}
