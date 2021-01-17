<?php
	
	namespace App\Http\Controllers\Api;
	
	use App\Events\Order\OrderPaymentConfirmedEvent;
	use App\Http\Controllers\Controller;
	use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
	use App\Models\Order;
	use GuzzleHttp\Client;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class OrderController extends Controller
	{
		
		public function index(Request $request)
		{
			return Order::with('user', 'shippable', 'shippingAddress')->get();
		}
		
		public function notificationList(Request $request)
		{
			$orders = [];
			if($request->user('manager')->can('manage branches')) {
				$orders = Order::where('status', 'issued')->with('user', 'shippingAddress')->get();
			} else {
				$orders = Order::where('status', 'pending')->with('user', 'shippingAddress')->get();
				
			}
			
			return $orders;
		}
		
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Order $order
		 * @return void
		 */
		public function update(Request $request, Order $order)
		{
			foreach($order->itemsQtyHolders as $holdQty) {
				UpdateAvailableQtyByInvoiceItemJob::dispatchNow($holdQty->invoiceItem, true);
//				$holdQty->update(
//					[
//						'status' => 'destroyed'
//					]
//				);
			}
			
			$order->update(
				[
					'status' => 'paid'
				]
			);
			event(new OrderPaymentConfirmedEvent($order));
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Order $order
		 * @return void
		 */
		public function destroy(Order $order)
		{
			//
		}


		public function signToDeliveryMan(Order $order,Request $request)
		{
			$request->validate([
				"delivey_man_id" => "required|integer|exists:delivery_men,id"
			]);


			
			
		}
		
	}



	// stock adjustment
	// sign deliveryMan to order
	// create awb ui 
	// whatsapp notification
	// delete model number form the name in online
	// online search dispaly all items and allow to select category => then filters (filters allowed for category only)