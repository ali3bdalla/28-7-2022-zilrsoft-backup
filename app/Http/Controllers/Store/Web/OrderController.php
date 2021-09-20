<?php

namespace App\Http\Controllers\Store\Web;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Order;
use App\Repository\OrderRepositoryContract;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    private OrderRepositoryContract $orderRepositoryContract;

    public function __construct(OrderRepositoryContract $orderRepositoryContract)
    {
        $this->orderRepositoryContract = $orderRepositoryContract;
    }

    public function cancelOrder(Order $order, Request $request): Response
    {
        $confirmationCode = (int)$request->input("code", 000);
        $this->orderRepositoryContract->ensureUserCanManipulateOrder($order, $confirmationCode);
        return Inertia::render(
            'Order/CancelOrder',
            [
                'order' => $order
            ]
        );

    }

    public function confirmPayment(Request $request, Order $order): Response
    {
        $confirmationCode = (int)$request->input("code", 000);
        $this->orderRepositoryContract->ensureUserCanManipulateOrder($order, $confirmationCode);
        return Inertia::render(
            'Order/ConfirmPayment', [
                'user' => $order->user,
                'code' => $request->input('code'),
                'order' => $order,
                'banks' => Bank::all(),
                'receivedBank' => Bank::where('account_id', '!=', null)->first(),
            ]
        );
    }
}
