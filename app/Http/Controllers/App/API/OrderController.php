<?php

namespace App\Http\Controllers\App\API;

use App\Events\Order\OrderPaymentConfirmedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\AcceptOrderRequest;
use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
use App\Jobs\Order\NotifyCustomerByOrderPaymentCancellationJob;
use App\Jobs\Order\Shipping\HandleOrderShippingJob;
use App\Models\DeliveryMan;
use App\Models\Manager;
use App\Models\Order;
use App\Notifications\Order\NewPaidOrderNotification;
use App\Notifications\Order\OrderPaymentAcceptedNotification;
use App\Repository\OrderRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class OrderController extends Controller
{

    private OrderRepositoryContract $orderRepositoryContract;

    public function __construct(OrderRepositoryContract $orderRepositoryContract)
    {
        $this->orderRepositoryContract = $orderRepositoryContract;
    }

    public function index(Request $request): LengthAwarePaginator
    {
        $query = Order::query();
        if (!$request->user('manager')->can('manage branches')) {
            $query->where('managed_by_id', auth()->user()->id);
        }
        return $query->with('user', 'shippingAddress', "shippingMethod", "deliveryMan", "managedBy")->orderBy('id', 'desc')->paginate(50);
    }

    public function notificationList(Request $request)
    {
        if ($request->user('manager')->can('manage branches')) {
            return Order::where('status', 'issued')->with('user', 'shippingAddress')->get();
        }
        return Order::where('status', 'pending')->with('user', 'shippingAddress')->get();
    }

    public function acceptOrder(AcceptOrderRequest $request, Order $order)
    {
        if (!$order->isPending()) abort(403);
        $this->orderRepositoryContract->acceptOrderPayment($order, $request->getTargetAccount());
        $order->user->notify(new OrderPaymentAcceptedNotification($order));
        Notification::send(Manager::all(), new NewPaidOrderNotification($order));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Order $order
     * @return void
     */
    public function destroy(Order $order)
    {
        foreach ($order->itemsQtyHolders()->get() as $holdQty) {
            UpdateAvailableQtyByInvoiceItemJob::dispatchSync($holdQty->invoiceItem, true);
        }
        $order->update([
            'status' => "canceled"
        ]);
        NotifyCustomerByOrderPaymentCancellationJob::dispatchSync($order);
    }


    public function signToDeliveryMan(Order $order, Request $request)
    {
        $request->validate([
            "delivery_man_id" => "required|integer|exists:delivery_men,id"
        ]);

        $deliveryMan = DeliveryMan::findOrFail($request->input('delivery_man_id'));

        $phoneNumber = $deliveryMan->phone_number;//
        $otp = generateOtp();
        sendOtp($phoneNumber, $otp);
        $deliveryMan->verfications()->create([
            'slug' => 'verify_signed_order_' . $order->id,
            'verfication_code' => $otp
        ]);

    }

    /**
     * @throws ValidationValidationException
     */
    public function activateSignToDeliveryMan(Order $order, Request $request)
    {
        $request->validate([
            "delivery_man_id" => "required|integer|exists:delivery_men,id",
            "verification_code" => "required|string",
        ]);

        $deliveryMan = DeliveryMan::findOrFail($request->input('delivery_man_id'));
        $verification = $deliveryMan->verfications()->where([
            ['slug', 'verify_signed_order_' . $order->id]
        ])->orderBy('id', 'desc')->first();

        if ($verification && $verification->verfication_code == $request->input('verification_code') && $order->status == 'ready_for_shipping') {
            HandleOrderShippingJob::dispatchSync($order, $deliveryMan);
        } else {

            throw  ValidationValidationException::withMessages([]);
        }

    }
}

