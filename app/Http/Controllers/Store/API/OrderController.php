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
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Manager;
use App\Models\Order;
use App\Notifications\Store\CanceledOrderNotification;
use App\Notifications\Store\UserConfirmedOrderPaymentNotification;
use App\Repository\OrderRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return $request->user()->orders()->whereHas("shippingMethod")->with("shippingMethod")->orderBy('id', 'desc')->paginate(50);
    }

    /**
     */
    public function store(StoreOrderRequest $storeOrderRequest): ?Order
    {
        return DB::transaction(function () {
            if (!Cart::canBeHandled() || !Cart::isReady()) {
                throw ValidationException::withMessages(['item_available_quantity' => "you can't sale this items , qty not"]);
            }
            $client = Auth::guard('client')->user();
            $cart = Cart::getSessionCart();
            $invoiceDto = new InvoiceDto(
                Manager::find(1),
                $client,
                InvoiceTypeEnum::sale(),
                Cart::list()->map(function (CartItem $cartItem) {
                    return [
                        'id' => $cartItem->item_id,
                        'price' => $cartItem->price,
                        'quantity' => $cartItem->quantity
                    ];
                })->toArray(),
                true,
                true
            );
            $orderDto = new OrderDto(
                $invoiceDto,
                $cart->shippingMethod,
                $cart->shippingAddress,
                "bank_transfer"
            );
            $order = $this->orderRepositoryContract->createOrder($orderDto);
            $this->orderRepositoryContract->issuedOrderNotifications($order);
            Cart::earse();
            return $order->load('user');
        });
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
            'Order/PaymentConfirmed',
            [
                'order' => $order
            ]
        );
    }
}
