<?php

namespace App\Notifications\Store;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserConfirmedOrderPaymentNotification extends Notification implements WhatsappMessageNotificationContract, ShouldQueue
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
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [WhatsappMessageChannel::class];
    }

    public function toWhatsappMessage($notifiable): string
    {
        return "عملية سداد جديدة
اسم العميل: {$this->order->user->locale_name}
رقم الطلب: {$this->order->id}
المبلغ المفترض:  {$this->order->net}
   رابط التاكيد : https://next.zilrsoft.com/web/orders/{$this->order->id}
";
    }
}
