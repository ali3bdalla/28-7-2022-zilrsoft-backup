<?php

namespace App\Http\Controllers\BackEnd\Store;

use AliAbdalla\Whatsapp\Whatsapp;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Application|Factory|View
	 */
	public function index()
	{
		return view('orders.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param Order $order
	 * @return Response
	 */
	public function show(Order $order)
	{
		$phoneNumber = $order->user->international_phone_number;



		// $message = view(
		// 	'whatsapp.order_details', [
		// 		'client' => $order->draftInvoice->user,
		// 		'order' => $order,
		// 		'invoice' => $order->draftInvoice,
		// 		'deadline' => Carbon::now()->addMinutes(30)->format('H:i')
		// 	]
		// )->toHtml();

		// return $phoneNumber;
		// if (app()->environment(['production', 'local'])) {
		// Whatsapp::sendMessage(
		// 	$phoneNumber, [$phoneNumber]
		// );
		// Whatsapp::sendFile(
		// 	$event->path, [$phoneNumber], $order->invoice->id
		// );
		// }


		$order->load('paymentDetail', 'user');
		return view('orders.show', compact('order'));
	}








	public function viewPayment(Order $order)
	{
		return view('orders.view-payment', compact('order'));
	}
	public function viewShipping(Order $order)
	{
		$shippingMen = $order->shippingMethod->deliveryMen()->get();
		return view('orders.view-shipping', compact('order', 'shippingMen'));
	}





	public function acceptOrderAsManager(Order $order)
	{
		if (!$order->status == 'paid') {
			return view('errors.custom');
		}

		$order->update(
			[
				'status' => 'in_progress',
				'managed_by_id' => auth()->user()->id
			]
		);

		return redirect('/sales/drafts/' . $order->draft_id . '/to_invoice');
	}
}