<?php
	
	namespace App\Http\Controllers\Web;
	
	use App\Http\Controllers\Controller;
	use App\Models\Category;
	use App\Models\Item;
	use Illuminate\Http\Request;
	use Inertia\Inertia;
	
	class HomeController extends Controller
	{
		
		public function toWeb()
		{
			
			return redirect(route('web.index',[
				
			]));
		}
		public function index()
		{
			// $categories = Category::where(
			// 	[
			// 		['parent_id', 0],
			// 		['is_available_online', true]
			// 	]
			// )->get();
			// $items = Item::where(
			// 	[
			// 		['is_available_online', false],
			// 		['available_qty', ">", 1],
			// 	]
			// )->inRandomOrder()->take(10)->get();
			
			return Inertia::render('Web/Home/Index',[
				// 'categories' => $categories,
				'items' => Item::with('category','filters.filter', 'filters.value')->inRandomOrder()->take(150)->get(),
			]);
			
//			return view('web.home.index', compact('categories', 'items'));
		}
		//
	}
