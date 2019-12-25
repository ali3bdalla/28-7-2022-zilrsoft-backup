<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Inventory\CreateBeginningRequest;
	use App\Http\Requests\Accounting\Inventory\DatatableBeginningRequest;
	use App\Http\Requests\CreateBeginningInventoryRequest;
	use App\Http\Requests\UpdateBeginningInventoryRequest;
	use App\User;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	
	class InventoryController extends Controller
	{
		
		public function __construct()
		{
			$this->middleware(['permission:manage inventory']);
		}
		
		/**
		 * @return Factory|View
		 */
		public function beginning_index()
		{
//			$inventories = Invoice::where('invoice_type','beginning_inventory')->orderBy('id','desc')->paginate(20);
			
			return view('accounting.inventories.beginning.index');
			
		}
		
		public function beginning_datatable(DatatableBeginningRequest $request)
		{
			return $request->data();
		}
		
		public function beginning_create()
		{
			$user = User::where([
				['user_slug','beginning-inventory'],
				['is_system_user',true]
			])->first();
			
			$creator = auth()->user()->with('department','branch')->first();
			
			return view('accounting.inventories.beginning.create',compact('user','creator'));
			
		}
		
		public function beginning_store(CreateBeginningRequest $request)
		{
			return $request->save();
		}
		
		
		
	}
