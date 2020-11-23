<?php
	
	namespace App\Http\Controllers\Api;
	
	use App\Http\Controllers\Controller;
	use App\Models\Order;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class OrderController extends Controller
	{
		
		public function index(Request $request)
		{
			return Order::with('user', 'shippingMethod', 'shippingAddress')->get();
		}
		
		public function notificationList(Request $request)
		{
			$orders = [];
			if($request->user('manager')->can('manage branches')) {
				$orders = Order::where('status', 'issued')->with('user','shippingAddress')->get();
			} else {
				$orders = Order::where('status', 'pending')->with('user','shippingAddress')->get();
				
			}
			
			return $orders;
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 * @return Response
		 */
		public function store(Request $request)
		{
			//
		}
		
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Order $order
		 * @return Response
		 */
		public function update(Request $request, Order $order)
		{
			//
		}
		
	}
