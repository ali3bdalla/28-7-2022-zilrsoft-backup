<?php

namespace App\Repository\Eloquent;

use App\Dto\OrderDto;
use App\Models\Order;
use App\Notifications\Store\IssuedOrderNotification;
use App\Repository\InvoiceRepositoryContract;
use App\Repository\OrderRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryContract
{
    private InvoiceRepositoryContract $invoiceRepositoryContract;

    public function __construct( InvoiceRepositoryContract $invoiceRepositoryContract)
    {
        $this->invoiceRepositoryContract = $invoiceRepositoryContract;
    }


    public function createOrder(OrderDto $orderDto): ?Order
    {
        return DB::transaction(function () use ($orderDto) {
            $draftInvoice = $this->invoiceRepositoryContract->createInvoice($orderDto->getInvoiceDto());
            $orderDto->setDraftInvoice($draftInvoice);
            return Order::factory()->setDto($orderDto)->create();
        });
    }

    public function issuedOrderNotifications(Order $order)
    {
        $user = $order->user;
        $user->notify(new IssuedOrderNotification(__('store.messages.notify_customer_by_new_order_message', [
            'CUSTOMER_NAME' => $order->user->name,
            'CANCEL_URL' => $order->generateCancelOrderUrl(),
            'PAYMENT_URL' => $order->generatePayOrderUrl(),
            'DEADLINE_TIME' => Carbon::now()->addMinutes(config('app.store.cancel_unpaid_orders_after'))->format('H:i'),
            'DEADLINE_DATE' => Carbon::now()->toDateString(),
            'AMOUNT' => moneyFormatter($order->net) . ' ' . __('store.products.sar'),
            'ORDER_ID' => $order->id,
        ])));
        $user->notify(new IssuedOrderNotification(
            __('store.messages.send_from_rajhi')
        ));
        $user->notify(new IssuedOrderNotification(
            "122608010398991"
        ));
        $user->notify(new IssuedOrderNotification(
            __('store.messages.send_from_other_banks_via_iban')
        ));
        $user->notify(new IssuedOrderNotification(
            "SA7280000122608010398991"
        ));
        $user->notify(new IssuedOrderNotification(
            __('store.messages.notify_customer_by_new_order_message_payment_link', [
                'CUSTOMER_NAME' => $order->user->name,
                'CANCEL_URL' => $order->generateCancelOrderUrl(),
                'PAYMENT_URL' => $order->generatePayOrderUrl(),
                'DEADLINE_TIME' => Carbon::now()->addMinutes(config('app.store.cancel_unpaid_orders_after'))->format('H:i'),
                'DEADLINE_DATE' => Carbon::now()->toDateString(),
                'AMOUNT' => moneyFormatter($order->net) . ' ' . __('store.products.sar'),
                'ORDER_ID' => $order->id,
            ])
        ));
    }
}
