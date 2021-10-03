<?php

namespace App\Repository\Eloquent;

use App\Dto\OrderDto;
use App\Dto\OrderPaymentDto;
use App\Dto\VoucherDto;
use App\Enums\OrderStatusEnum;
use App\Enums\VoucherTypeEnum;
use App\Models\Account;
use App\Models\Order;
use App\Notifications\Store\IssuedOrderNotification;
use App\Repository\EntryRepositoryContract;
use App\Repository\InvoiceRepositoryContract;
use App\Repository\OrderRepositoryContract;
use App\ValueObjects\MoneyValueObject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryContract
{
    private InvoiceRepositoryContract $invoiceRepositoryContract;
    private EntryRepositoryContract $entryRepositoryContract;
    private VoucherRepository $voucherRepository;

    public function __construct(InvoiceRepositoryContract $invoiceRepositoryContract, EntryRepositoryContract $entryRepositoryContract, VoucherRepository $voucherRepository)
    {
        $this->invoiceRepositoryContract = $invoiceRepositoryContract;
        $this->entryRepositoryContract = $entryRepositoryContract;
        $this->voucherRepository = $voucherRepository;
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
            'AMOUNT' => (new MoneyValueObject($order->net, "SAR"))->getFormattedMoney(),
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
                'AMOUNT' => (new MoneyValueObject($order->net, "SAR"))->getFormattedMoney(),
                'ORDER_ID' => $order->id,
            ])
        ));
    }

    public function ensureUserCanManipulateOrder(Order $order, int $confirmationKey)
    {
        if (!$order->status->equals(OrderStatusEnum::issued()) || (int)$order->order_secret_code !== $confirmationKey) {
            abort(404);
        }
    }

    public function registerOrderPayment(Order $order, OrderPaymentDto $orderPaymentDto)
    {
        return DB::transaction(function () use ($order, $orderPaymentDto) {
            $this->changeOrderStatus($order, OrderStatusEnum::pending());
            return $order->paymentDetail()->create(['user_id' => $order->user_id, 'sender_account_id' => $orderPaymentDto->getSenderAccountId(),
                    'received_bank_id' => $orderPaymentDto->getReceiverAccountId(), 'first_name' => $orderPaymentDto->getFirstName(), 'last_name' => $orderPaymentDto->getLastName(),]
            );

        });
    }

    public function changeOrderStatus(Order $order, OrderStatusEnum $orderStatusEnum)
    {
        $order->update([
            'status' => $orderStatusEnum
        ]);
    }

    public function acceptOrderPayment(Order $order, Account $account)
    {
        return DB::transaction(function () use ($order, $account) {
            $this->changeOrderStatus($order, OrderStatusEnum::paid());
            $order->update(['payment_approved_at' => now(), 'payment_approved_by_id' => $this->authManager()->id ]);
            $voucherDto = new VoucherDto(
                $account,
                $this->authManager(), $order->user,$order->net,VoucherTypeEnum::receipt(),'ORDER PAYMENT');
            $voucher = $this->voucherRepository->createVoucher($voucherDto);
            $this->entryRepositoryContract->registerReceiptVoucherEntry($voucher, $account);
            return $order;
        });
    }
}
