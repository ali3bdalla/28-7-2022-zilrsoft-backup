<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\CreateBeginningInventoryRequest;
	use App\Http\Requests\UpdateBeginningInventoryRequest;
	use App\Invoice;
	use App\PurchaseInvoice;
	use App\User;
	use Illuminate\Http\Request;
	
	class InventoryController extends Controller
	{
		
		public function index()
		{
			return view('inventories.beginning.index');
			
		}
		
		public function beginning()
		{
			$inventories = Invoice::where('invoice_type','beginning_inventory')->orderBy('id','desc')->paginate(20);
			
			return view('inventories.beginning.index',compact('inventories'));
			
		}
		
		public function beginning_create()
		{
			$user = User::where([
				['user_slug','beginning-inventory'],
				['is_system_user',true]
			])->first();
			
			$creator = auth()->user()->with('department','branch')->first();

//			return $creator;
			return view('inventories.beginning.create',compact('user','creator'));
			
		}
		
		public function beginng_edit(PurchaseInvoice $inventory)
		{
			$init_items = $inventory->invoice->items()->with([
				'item.serials' => (function ($query) use ($inventory){
					return $query->where('item_serials.purchase_invoice_id',$inventory->invoice_id);
				})
			])->get();
			
			
			$items = [];
			foreach ($init_items as $item){
				$item->name = $item->item->name;
				$item->is_need_serials = $item->item->is_need_serials;
				$item->serials = $item->item->serials;
				$item->purchase_price = $item->price;
				$item->has_been_deleted = false;
				$item->isOpen = false;
				
				
				$items[] = $item;
				
			}
			
			return view('inventories.beginning.edit',compact('inventory','items'));
			
		}
		
		public function beginng_update(UpdateBeginningInventoryRequest $request,PurchaseInvoice $inventory)
		{
			
			return $request->save($inventory);
		}
		
		public function beginning_store(CreateBeginningInventoryRequest $request)
		{
			
			return $request->save();
		}
		
	}
