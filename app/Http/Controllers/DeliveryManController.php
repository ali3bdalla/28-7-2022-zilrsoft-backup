<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryMan\StoreDeliveryManRequest;
use App\Jobs\Order\NotifyCustomerOrderHasBeenShippedJob;
use App\Models\City;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\ShippingTransaction;
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
		$transactions = ShippingTransaction::where([
			['delivery_man_id', $deliveryMan->id],

			['order_id', '!=', null],
		])->paginate(25);
		

		return view('delivery_men.confirm', compact('deliveryMan', 'transactions'));
	}


	public function performConfirm($hash,ShippingTransaction $transaction, Request $request)
	{
		$request->validate(
			[
				'code' => 'required|string'
			]
		);

		$deliveryMan = DeliveryMan::where('hash', $hash)->firstOrFail();


		if ($transaction->order && $transaction->order->order_secret_code === $request->input('code') && $deliveryMan->id === $transaction->delivery_man_id) {
			$transaction->order->update(
				[
					'status' => 'delivered'
				]
			);
			$transaction->update([
				'status' => 'received'
			]);
			return;
		}

		throw ValidationException::withMessages(
			[
				'code' => 'invalid code'
			]
		);
	}

	public function resendOtp(ShippingTransaction $transaction)
	{

		if($transaction->order){
			NotifyCustomerOrderHasBeenShippedJob::dispatchNow($transaction->order);
		}
	}
}
