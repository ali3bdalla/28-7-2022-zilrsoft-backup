<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Item;
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
