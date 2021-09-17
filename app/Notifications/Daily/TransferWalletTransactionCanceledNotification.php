<?php

namespace App\Notifications\Daily;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\ResellerClosingAccount;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransferWalletTransactionCanceledNotification extends Notification implements WhatsappMessageNotificationContract
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
        //
        $this->pendingWalletTransferTransaction = $pendingWalletTransferTransaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsappMessageChannel::class];
    }

    public function toWhatsappMessage($notifiable): string
    {
        $formattedAmount = (new MoneyValueObject($this->pendingWalletTransferTransaction->amount, 'SAR'))->getFormattedMoney();
        return "Your Transfer to {$this->pendingWalletTransferTransaction->toAccount->locale_name},
 amount: $formattedAmount
has been *canceled*";
    }
}
