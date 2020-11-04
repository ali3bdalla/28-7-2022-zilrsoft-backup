<?php
	
	namespace App\Http\Controllers\Web;
	
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Inertia\Inertia;
	
	class CartController extends Controller
	{
		public function index(Request $request)
		{
			return Inertia::render(
				'Web/Cart/Index', [
				'shippingAddresses' => $request->user('client')->shippingAddresses()->with('country')->get()
			]
			);
		}
	}
