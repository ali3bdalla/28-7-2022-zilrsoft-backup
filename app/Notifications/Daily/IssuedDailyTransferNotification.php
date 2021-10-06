<?php

namespace App\Notifications\Daily;

use App\Channels\BroadcastNotificationContract;
use App\Channels\OurSmsChannel;
use App\Channels\OurSmsNotificationContract;
use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\ResellerClosingAccount;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class IssuedDailyTransferNotification extends Notification implements
    WhatsappMessageNotificationContract,
    ShouldQueue,
    OurSmsNotificationContract,
    BroadcastNotificationContract
{
    use Queueable;

    private ResellerClosingAccount $pendingWalletTransaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ResellerClosingAccount $pendingWalletTransaction)
    {
        $this->pendingWalletTransaction = $pendingWalletTransaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [WhatsappMessageChannel::class,OurSmsChannel::class,'database', "broadcast"];
    }


    public function toWhatsappMessage($notifiable): string
    {
        $confirmLink = shortLink(route('api.daily.wallet.confirm_transfer', $this->pendingWalletTransaction->id));
        $cancelLink = shortLink(route('api.daily.wallet.cancel_transfer', $this->pendingWalletTransaction->id));
        $mangerName = $this->pendingWalletTransaction->creator->name;
        $formattedAmount = (new MoneyValueObject($this->pendingWalletTransaction->amount, 'SAR'))->getFormattedMoney();
        return "تحويل من  *{$mangerName}* الى  *{$this->pendingWalletTransaction->toAccount->locale_name}*
        
المبلغ: *{$formattedAmount}*

تاكيد : {$confirmLink}


الغاء: {$cancelLink}";
    }

    public function toOurSms($notifiable): string
    {
        return $this->toWhatsappMessage($notifiable);
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        $data = $this->toArray($notifiable);
        return new BroadcastMessage([
            'message' => $data['message'],
            'actions' => $data['actions']
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $confirmLink = shortLink(route('api.daily.wallet.confirm_transfer', $this->pendingWalletTransaction->id));
        $cancelLink = shortLink(route('api.daily.wallet.cancel_transfer', $this->pendingWalletTransaction->id));
        $mangerName = $this->pendingWalletTransaction->creator->name;
        $formattedAmount = (new MoneyValueObject($this->pendingWalletTransaction->amount, 'SAR'))->getFormattedMoney();
        return [
            'message' => "تحويل من  {$mangerName} الى  {$this->pendingWalletTransaction->toAccount->locale_name} {$formattedAmount}",
            'actions' => [
                [
                    "title" => "قبول",
                    "action" => $confirmLink,
                    "method" => "get"
                ],
                [
                    "title" => "الغاء",
                    "action" => $cancelLink,
                    "method" => "get"
                ]
            ]
        ];
    }
}
