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

		return redirect(route('web.index', []));
	}
	public function index()
	{


		return Inertia::render('Web/Home/Index', [
			'products_count' => Item::count(),
			'params' => [],
			'latest' => Item::orderBy('created_at','desc')->take(15)->get(),
			'heigest_price' => Item::orderBy('online_offer_price','desc')->take(15)->get(),
			// 'items' => Item::with('category', 'filters.filter', 'filters.value')->inRandomOrder()->take(20)->get(),
		]);
	}
}
