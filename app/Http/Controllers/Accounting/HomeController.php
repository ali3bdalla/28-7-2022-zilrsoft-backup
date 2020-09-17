<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Models\Item;
	use App\Models\WarrantySubscription;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	
	
	class HomeController extends Controller
	{
		public function __construct(){
			$this->middleware('auth');
		}
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
//			WarrantySubscription::createInit();
			
//			$items = Item::take(10)->get();
//			$list = [];
//			foreach ($items as $item)
//			{
////
//				$list[] = $item->getOriginal('price');
//			}
//			return  $list;
			return view('accounting.dashboard.index');
		}
		
	}
