<?php

namespace App\Http\Controllers\Store\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use App\Notifications\Store\IssuedOrderNotification;
use App\Notifications\Store\IssuedOrderPaymentInstructionsNotification;
use App\Notifications\Store\SendBankAccountNumberNotification;
use App\Notifications\Store\SendBankNameNotification;
use App\Notifications\Store\SendPaymentLinkNotification;
use App\Repository\OrderRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    private OrderRepositoryContract $orderRepositoryContract;

    public function __construct(OrderRepositoryContract $orderRepositoryContract)
    {
        $this->orderRepositoryContract = $orderRepositoryContract;
    }

    public function index(Request $request)
    {
        return $request->user()->orders()->with("shippingMethod")->orderBy('id', 'desc')->paginate(50);
    }


    public function store(StoreOrderRequest $storeOrderRequest): ?Order
    {
        $items = $storeOrderRequest->getItems();
        $shippingMethod = $storeOrderRequest->getShippingMethod();
        $shippingAddress = $storeOrderRequest->getShippingAddress();
        $paymentMethodId = $storeOrderRequest->getPaymentMethodId();
        $client = Auth::guard('client')->user();
        $order = $this->orderRepositoryContract->createOrder($client, $items, $shippingMethod, $shippingAddress, $paymentMethodId);
        $client->notify(new IssuedOrderNotification($order));
        $client->notify(new IssuedOrderPaymentInstructionsNotification($order));
        $client->notify(new SendBankNameNotification());
        $client->notify(new SendBankAccountNumberNotification());
        $client->notify(new SendPaymentLinkNotification($order));
        return $order;
    }
}
