<?php

namespace App\Http\Controllers\Api;

use AliAbdalla\Whatsapp\Whatsapp;
use App\Events\Order\OrderPaymentConfirmedEvent;
use App\Http\Controllers\Controller;
use App\Jobs\Accounting\CreateReceivedPaymentFromClientJob;
use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
use App\Jobs\Order\ConfirmPaymentJob;
use App\Jobs\Order\NotifyCustomerByOrderPaymentCancellationJob;
use App\Jobs\Order\NotifyCustomerByPaymentConfirmationJob;
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
		return Order::with('user', 'shippable', 'shippingAddress')->paginate(50);
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
	    $request->validate([
	        'account_id' => 'required|integer|exists:accounts,id'
        ]);
		if($order->status === 'pending')
		{
			foreach ($order->itemsQtyHolders()->get() as $holdQty) {
				UpdateAvailableQtyByInvoiceItemJob::dispatchNow($holdQty->invoiceItem, true);
			}
			$order->update(
				[
					'status' => 'paid'
				]
			);
            CreateReceivedPaymentFromClientJob::dispatchNow($order->user,$order->net,$request->input('account_id'));
            event(new OrderPaymentConfirmedEvent($order));
            NotifyCustomerByPaymentConfirmationJob::dispatchNow($order);
		}


	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Order $order
	 * @return void
	 */
	public function destroy(Order $order)
	{
		foreach ($order->itemsQtyHolders()->get() as $holdQty) {
			UpdateAvailableQtyByInvoiceItemJob::dispatchNow($holdQty->invoiceItem, true);
		}
		$order->update([
			'status' => "canceled"
		]);
		NotifyCustomerByOrderPaymentCancellationJob::dispatchNow($order);
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
