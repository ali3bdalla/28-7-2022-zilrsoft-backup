<?php

namespace App\Http\Controllers\Store\API;

use App\Dto\InvoiceDto;
use App\Dto\OrderDto;
use App\Enums\InvoiceTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Manager;
use App\Models\Order;
use App\Repository\OrderRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    /**
     * @throws ValidationException
     */
    public function store(StoreOrderRequest $storeOrderRequest): ?Order
    {
        $storeOrderRequest->ensureQuantitiesAreValid();
        $client = Auth::guard('client')->user();
        $items = $storeOrderRequest->getItems();
        $shippingMethod = $storeOrderRequest->getShippingMethod();
        $shippingAddress = $storeOrderRequest->getShippingAddress();
        $paymentMethodId = $storeOrderRequest->getPaymentMethodId();
        $invoiceDto = new InvoiceDto(Manager::find(1), $client, InvoiceTypeEnum::sale(), $items, true, true);
        $orderDto = new OrderDto($invoiceDto, $shippingMethod, $shippingAddress, $paymentMethodId);
        $order = $this->orderRepositoryContract->createOrder($orderDto);
        $this->orderRepositoryContract->issuedOrderNotifications($order);
        return $order;
    }
}
