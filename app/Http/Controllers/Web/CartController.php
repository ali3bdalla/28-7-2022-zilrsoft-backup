<?php
	
	namespace App\Http\Controllers\Web;
	
	use App\Http\Controllers\Controller;
    use App\Models\ShippingMethod;
    use Illuminate\Http\Request;
	use Inertia\Inertia;
	
	class CartController extends Controller
	{
		public function index(Request $request)
		{
			
			$data = [
			];
			
			if(auth('client')->check()) {
				$data['shippingAddresses'] = $request->user('client')->shippingAddresses()->with('city')->get();
			}


            $data['shippingMethods'] = ShippingMethod::all();
			
			return Inertia::render(
				'Web/Cart/Index',
				$data
			);
		}
	}
