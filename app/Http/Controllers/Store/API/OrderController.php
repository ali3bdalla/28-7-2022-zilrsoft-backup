<?php

namespace App\Http\Controllers\Store\API;

use App\Dto\InvoiceDto;
use App\Dto\OrderDto;
use App\Dto\OrderPaymentDto;
use App\Enums\InvoiceTypeEnum;
use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Web\Order\ConfirmOrderPaymentRequest;
use App\Models\Manager;
use App\Models\Order;
use App\Notifications\Store\CanceledOrderNotification;
use App\Notifications\Store\UserConfirmedOrderPaymentNotification;
use App\Repository\OrderRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

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
        return $order->load('user');
    }

    public function cancelOrder(Order $order, Request $request)
    {
        $confirmationCode = (int)$request->input("code", 000);
        $this->orderRepositoryContract->ensureUserCanManipulateOrder($order, $confirmationCode);
        $this->orderRepositoryContract->changeOrderStatus($order, OrderStatusEnum::canceled());
        $order->user()->notify(new CanceledOrderNotification($order, true));
    }

    public function confirmPayment(ConfirmOrderPaymentRequest $request, Order $order): Response
    {
        $confirmationCode = (int)$request->input("code", 000);
        $this->orderRepositoryContract->ensureUserCanManipulateOrder($order, $confirmationCode);
        $orderPaymentDto = new OrderPaymentDto(
            $request->getFirstName(),
            $request->getLastName(),
            $request->getSendAccountId(),
            $request->getReceiverBankId()
        );
        $this->orderRepositoryContract->registerOrderPayment($order, $orderPaymentDto);
        Notification::send(Manager::whereOrganizationId($order->organization_id)->get(), new UserConfirmedOrderPaymentNotification($order));
        return Inertia::render(
            'Order/PaymentConfirmed', [
                'order' => $order
            ]
        );
    }
}
