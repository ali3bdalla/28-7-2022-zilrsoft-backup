<?php

namespace App\Notifications\Store;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
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
        return [WhatsappMessageChannel::class, 'database', "broadcast"];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        $data = $this->toDatabase($notifiable);
        return new BroadcastMessage([
            'message' => $data['message'],
            'actions' => $data['actions']
        ]);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "عملية سداد لطلب من المتجر الالكتروني من  {$this->order->user->locale_name} ، قيمة المبلغ ، {$this->order->net}",
            'actions' => [
                [
                    "title" => "عرض السداد",
                    "action" => $this->viewPaymentLink(),
                    "method" => "get"
                ],
            ]
        ];
    }

    private function viewPaymentLink(): string
    {
        return route('store.orders.view-payment', $this->order->id);
    }

    public function toWhatsappMessage($notifiable): string
    {
        return "عملية سداد جديدة
اسم العميل: {$this->order->user->locale_name}
رقم الطلب: {$this->order->id}
المبلغ المفترض:  {$this->order->net}
   رابط التاكيد : {$this->viewPaymentLink()}
";
    }
}
