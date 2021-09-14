<?php

namespace App\Notifications\Store;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\Order;
use App\Models\User;
use App\ValueObjects\MoneyValueObject;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class IssuedOrderNotification extends Notification implements WhatsappMessageNotificationContract,ShouldQueue
{
    use Queueable;

    private Order $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param User $notifiable
     * @return array
     */
    public function via(User $notifiable): array
    {
        return [WhatsappMessageChannel::class];
    }


    public function toWhatsappMessage($notifiable): string
    {
        return __('store.messages.notify_customer_by_new_order_message', [
            'CUSTOMER_NAME' => $this->order->user->name,
            'CANCEL_URL' => $this->order->generateCancelOrderUrl(),
            'PAYMENT_URL' => $this->order->generatePayOrderUrl(),
            'DEADLINE_TIME' => Carbon::now()->addMinutes(config('app.store.cancel_unpaid_orders_after'))->format('H:i'),
            'DEADLINE_DATE' => Carbon::now()->toDateString(),
            'AMOUNT' => (new MoneyValueObject($this->order->net,'SAR'))->getFormattedMoney(),
            'ORDER_ID' => $this->order->id,
        ]);
    }
}
