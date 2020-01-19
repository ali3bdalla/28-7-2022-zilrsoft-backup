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
//			$items = Item::all();
//			$prices = [];
//			foreach ($items as $item)
//			{
//				if($item->price_with_tax!=null && $item->price_with_tax>0)
//				{
//
//					$price = $item->price_with_tax / (1 + ($item->vts/100));
//					$item->update([
//						'price' => $price
//					]);
//					$prices[] =[
//						$item->price_with_tax,
//						$item->price,
//					];
//				}
//
//			}
//			return  $prices;
			return view('accounting.dashboard.index');
		}
		
	}
