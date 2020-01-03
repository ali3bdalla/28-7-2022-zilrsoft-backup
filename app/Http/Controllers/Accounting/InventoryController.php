<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Inventory\CreateBeginningRequest;
	use App\Http\Requests\Accounting\Inventory\DatatableBeginningRequest;
	use App\Http\Requests\CreateBeginningInventoryRequest;
	use App\Http\Requests\UpdateBeginningInventoryRequest;
	use App\Invoice;
	use App\InvoicePayments;
	use App\Payment;
	use App\PurchaseInvoice;
	use App\Transaction;
	use App\TransactionsContainer;
	use App\User;
	use Exception;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	
	class InventoryController extends Controller
	{
		
		/**
		 * InventoryController constructor.
		 */
		public function __construct()
		{
			$this->middleware(['permission:manage inventory']);
		}
		
		/**
		 * @return Factory|View
		 */
		public function beginning_index()
		{
			return view('accounting.inventories.beginning.index');
		}
		
		/**
		 * @param DatatableBeginningRequest $request
		 *
		 * @return mixed
		 */
		public function beginning_datatable(DatatableBeginningRequest $request)
		{
			return $request->data();
		}
		
		/**
		 * @return Factory|View
		 */
		public function beginning_create()
		{
			$user = User::where([
				['user_slug','beginning-inventory'],
				['is_system_user',true]
			])->first();
			
			$creator = auth()->user()->with('department','branch')->first();
			return view('accounting.inventories.beginning.create',compact('user','creator'));
			
		}
		
		/**
		 * @param CreateBeginningRequest $request
		 *
		 * @throws Exception
		 */
		public function beginning_store(CreateBeginningRequest $request)
		{
			return $request->save();
		}
		
		/**
		 * @param Invoice $beginning
		 */
		public function beginning_destroy(Invoice $beginning)
		{
			TransactionsContainer::where('invoice_id',$beginning->id)->forceDelete();
			Transaction::where('invoice_id',$beginning->id)->forceDelete();
			Payment::where('invoice_id',$beginning->id)->forceDelete();
			InvoicePayments::where('invoice_id',$beginning->id)->forceDelete();
			foreach ($beginning->items as $item){
				$current_qty = $item->item->available_qty - $item['qty'];
				$item->item->update([
					'available_qty' => $current_qty,
				]);
				if ($item->item->is_need_serial){
					$item->item->serials()->where('purchase_invoice_id',$beginning->id)->forceDelete();
				}
				
			}
			
			$beginning->items()->forceDelete();
			$beginning->forceDelete();
		}
		
	}
