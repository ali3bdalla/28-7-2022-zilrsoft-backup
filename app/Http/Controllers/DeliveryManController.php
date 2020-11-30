<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\DeliveryMan\StoreDeliveryManRequest;
	use App\Models\City;
	use App\Models\DeliveryMan;
	use App\Models\Order;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\Validation\ValidationException;
	
	class DeliveryManController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			return view('delivery_men.index');
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			
			$cities = City::all();
			return view('delivery_men.create', compact('cities'));
			
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param StoreDeliveryManRequest $request
		 * @return void
		 */
		public function store(StoreDeliveryManRequest $request)
		{
			return $request->store();
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function show(DeliveryMan $deliveryMan)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function edit(DeliveryMan $deliveryMan)
		{
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function update(Request $request, DeliveryMan $deliveryMan)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function destroy(DeliveryMan $deliveryMan)
		{
			//
		}
		
		public function confirm($hash)
		{
			$deliveryMan = DeliveryMan::where('hash', $hash)->firstOrFail();
			$orders = $deliveryMan->orders()->with('user', 'shippingAddress')->get();//->where('status', 'shipped')

			return view('delivery_men.confirm', compact('deliveryMan', 'orders'));
		}
		
		
		public function performConfirm($hash, $orderId, Request $request)
		{
			$request->validate(
				[
					'code' => 'required|string'
				]
			);
			
			$deliveryMan = DeliveryMan::where('hash', $hash)->firstOrFail();
			$order = Order::where(
				[
//					['status', 'shipped'],
					['id', $orderId],
//					['shippable_id', $deliveryMan->id],
//					['shippable_type' , class_basename($deliveryMan)],
				]
			)->firstOrFail();
			
			
			if($order->order_secret_code == $request->input('code')) {
				
				
				$order->update(
					[
						'status' => 'delivered'
					]
				);
				
//				$order->activities()->create(
//					[
//						'doable_type' => class_basename($deliveryMan),
//						'doable_id' => $deliveryMan->id,
//						'activity' => 'delivered'
//					]
//				);
				return;
			}
			
			throw ValidationException::withMessages(
				[
					'code' => 'invalid code'
				]
			);
		}
		
		
	}
