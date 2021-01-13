<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\Accounting\Inventory\CreateBeginningRequest;
	use App\Http\Requests\Accounting\Inventory\DatatableBeginningRequest;
	use App\Http\Requests\Accounting\Inventory\ReturnBeginningRequest;
	use App\Models\Invoice;
	use App\Models\Payment;
	use App\Models\Transaction;
	use App\Models\TransactionsContainer;
	use App\Models\User;
	use Exception;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\View\View;
	
	class InventoryController extends Controller
	{
		//
		
		/**
		 * @return Factory|View
		 */
		public function index()
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
		public function create()
		{
			$user = User::where(
				[
					['user_slug', 'beginning-inventory'],
					['is_system_user', true]
				]
			)->first();
			$creator = auth()->user()->with('department', 'branch')->first();
			return view('accounting.inventories.beginning.create', compact('user', 'creator'));
			
		}
		

		public function adjustements()
		{
			return view('accounting.inventories.adjust_stock.index');
		}

		public function createAdjustement()
		{
			$user = User::where([
				['user_slug','beginning-inventory'],
				['is_system_user',true]
			])->first();
			
			$creator = auth()->user()->with('department','branch')->first();
			return view('accounting.inventories.adjust_stock.create',compact('user','creator'));
		}
	}
