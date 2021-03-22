<?php

namespace App\Http\Controllers\Web\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Order\ConfirmOrderPaymentRequest;
use App\Models\Bank;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfirmOrderPaymentController extends Controller
{


    public function showConfirmPaymentPage(Request $request, Order $order)
    {


        if (!$this->isValidOrderStatus($order) || !$this->isValidKey($order, $request)) {
            return Inertia::render(
                'Web/Order/OrderConfirmationExpired', [
                    'order' => $order
                ]
            );

        }


        return Inertia::render(
            'Web/Order/ConfirmPayment', [
                'user' => $order->user,
                'code' => $request->input('code'),
                'order' => $order,
                'banks' => Bank::all(),
                'receivedBank' => Bank::where('account_id', '!=', null)->first(),
            ]
        );
    }

    private function isValidOrderStatus($order)
    {
        return $order->status === 'issued';

    }

    private function isValidKey(Order $order, Request $request)
    {
        return $request->input('code') == $order->order_secret_code;
    }

    public function confirmPayment(ConfirmOrderPaymentRequest $request, Order $order)
    {

        if (!$this->isValidOrderStatus($order) || !$this->isValidKey($order, $request)) {
            return Inertia::render(
                'Web/Order/OrderConfirmationExpired', [
                    'order' => $order
                ]
            );
        }
        $request->confirm($order);
        return Inertia::render(
            'Web/Order/PaymentConfirmed', [
                'order' => $order
            ]
        );
    }


}
