<?php

namespace App\Http\Controllers\Api;

use AliAbdalla\Whatsapp\Whatsapp;
use App\Events\Order\OrderPaymentConfirmedEvent;
use App\Http\Controllers\Controller;
use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
use App\Jobs\Order\Shipping\HandleOrderShippingJob;
use App\Models\DeliveryMan;
use App\Models\Order;
use Dotenv\Exception\ValidationException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class OrderController extends Controller
{

	public function index(Request $request)
	{
		return Order::with('user', 'shippable', 'shippingAddress')->get();
	}

	public function notificationList(Request $request)
	{
		$orders = [];
		if ($request->user('manager')->can('manage branches')) {
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
		foreach ($order->itemsQtyHolders as $holdQty) {
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


	public function signToDeliveryMan(Order $order, Request $request)
	{
		$request->validate([
			"delivery_man_id" => "required|integer|exists:delivery_men,id"
		]);

		$deliveryMan = DeliveryMan::findOrFail($request->input('delivery_man_id'));

		$phoneNumber = '+966556045415';//$deliveryMan->phone_number
		$otp = generateOtp();
		sendOtp($phoneNumber, $otp);

		// Whatsapp::sendMessage(
		// 	"verification code for accepting order {$order->id} is: {$otp }", [$phoneNumber]
		// );
		$deliveryMan->verfications()->create([
			'slug' => 'verify_signed_order_' . $order->id,
			'verfication_code' => $otp
		]);

	}
	public function activateSignToDeliveryMan(Order $order, Request $request)
	{
		$request->validate([
			"delivery_man_id" => "required|integer|exists:delivery_men,id",
			"verification_code" => "required|integer",
		]);

		$deliveryMan = DeliveryMan::findOrFail($request->input('delivery_man_id'));
		$verification = $deliveryMan->verfications()->where([
			['slug', 'verify_signed_order_' . $order->id]
		])->orderBy('id','desc')->first();

		if($verification && $verification->verfication_code == $request->input('verification_code') && $order->status == 'ready_for_shipping') {
			HandleOrderShippingJob::dispatchNow($order,$deliveryMan);
		}else{

			throw  ValidationValidationException::withMessages([]);
		}

	}
}



	// sign deliveryMan to order
	// create awb ui 
	// whatsapp notification
