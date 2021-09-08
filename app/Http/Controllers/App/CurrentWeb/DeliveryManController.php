<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryMan\StoreDeliveryManRequest;
use App\Jobs\Order\NotifyCustomerOrderHasBeenDeliveredJob;
use App\Jobs\Order\NotifyCustomerOrderHasBeenShippedJob;
use App\Models\City;
use App\Models\DeliveryMan;
use App\Models\ShippingTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DeliveryManController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('delivery_men.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
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
     * @return Application|Redirector|RedirectResponse
     */
    public function store(StoreDeliveryManRequest $request)
    {
        return $request->store();
    }


    public function confirm($hash)
    {
        $deliveryMan = DeliveryMan::where('hash', $hash)->firstOrFail();
        $transactions = ShippingTransaction::where([
            ['delivery_man_id', $deliveryMan->id],

            ['order_id', '!=', null],
        ])->orderBy('id', 'desc')->paginate(25);


        return view('delivery_men.confirm', compact('deliveryMan', 'transactions'));
    }


    public function performConfirm($hash, ShippingTransaction $transaction, Request $request)
    {
        $request->validate(
            [
                'code' => 'required|string'
            ]
        );

        $deliveryMan = DeliveryMan::where('hash', $hash)->firstOrFail();


        if ($transaction->order && $transaction->order->delivery_man_code === $request->input('code') && $deliveryMan->id === $transaction->delivery_man_id) {
            $transaction->order->update(
                [
                    'status' => 'delivered'
                ]
            );
            $transaction->update([
                'delivered_at' => Carbon::now(),
                'status' => 'received'
            ]);
            NotifyCustomerOrderHasBeenDeliveredJob::dispatch($transaction->order);
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

        if ($transaction->order) {
            NotifyCustomerOrderHasBeenShippedJob::dispatch($transaction->order);
        }
    }
}
