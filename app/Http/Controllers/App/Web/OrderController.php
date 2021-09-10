<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
     * @return Application|Factory|View
     */
    public function show(Order $order)
    {
        $order->load('paymentDetail', 'user');
        return view('orders.show', compact('order'));
    }


    public function viewPayment(Order $order)
    {
        $accounts = Auth::user()->gateways()->get();
        return view('orders.view-payment', compact('order', 'accounts'));
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

        return redirect('/sales/' . $order->draft_id);
    }
}
