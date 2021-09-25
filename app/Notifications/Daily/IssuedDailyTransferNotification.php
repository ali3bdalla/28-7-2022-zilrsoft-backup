<?php

namespace App\Notifications\Daily;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\ResellerClosingAccount;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class IssuedDailyTransferNotification extends Notification implements WhatsappMessageNotificationContract,ShouldQueue
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
        return [WhatsappMessageChannel::class, 'database'];
    }

    public function toDatabase(): array
    {
        return $this->pendingWalletTransaction->toArray();
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
}
