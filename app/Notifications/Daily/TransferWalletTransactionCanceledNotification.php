<?php

namespace App\Notifications\Daily;

use App\Channels\BroadcastNotificationContract;
use App\Channels\OurSmsNotificationContract;
use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Dto\BroadcastNotificationDto;
use App\Models\ResellerClosingAccount;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\BroadcastChannel;
use Illuminate\Notifications\Notification;

class TransferWalletTransactionCanceledNotification extends Notification implements
    WhatsappMessageNotificationContract,
    ShouldQueue,
    OurSmsNotificationContract,
    BroadcastNotificationContract
{
    use Queueable;

    private ResellerClosingAccount $pendingWalletTransferTransaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ResellerClosingAccount $pendingWalletTransferTransaction)
    {
        $this->pendingWalletTransferTransaction = $pendingWalletTransferTransaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [WhatsappMessageChannel::class,OurSmsNotificationContract::class,'database', "broadcast"];
    }

    public function toWhatsappMessage($notifiable): string
    {
        $formattedAmount = (new MoneyValueObject($this->pendingWalletTransferTransaction->amount, 'SAR'))->getFormattedMoney();
        return "تم رفض التحويل الى خزينة {$this->pendingWalletTransferTransaction->toAccount->locale_name},
 المبلغ : $formattedAmount";
    }
    public function toOurSms($notifiable): string
    {
        return $this->toWhatsappMessage($notifiable);
    }

    public function toBroadcast($notifiable): BroadcastNotificationDto
    {
        $data = $this->toArray($notifiable);
        return new BroadcastNotificationDto($data['message'], $data['actions']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'message' => $this->toWhatsappMessage($notifiable),
            'actions' => []
        ];
    }

}
