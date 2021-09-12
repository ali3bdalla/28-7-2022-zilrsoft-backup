<?php

namespace App\Notifications\User;

use App\Channels\OurSmsChannel;
use App\Channels\OurSmsNotificationContract;
use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class PasswordResetVerificationCodeNotification extends Notification implements WhatsappMessageNotificationContract, OurSmsNotificationContract,ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsappMessageChannel::class, OurSmsChannel::class];
    }

    public function toOurSms($notifiable): string
    {
        return 'click to reset password : ' . $this->createVerificationUrl($notifiable);
    }

    private function createVerificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'web.forget_password.reset.index',
            Carbon::now()->addMinutes(15),
            [
                'id' => $notifiable->getKey(),
                'verification_code' => $notifiable->verification_code,
                'hash' => sha1($notifiable->phone_number),
            ]
        );

    }

    public function toWhatsappMessage($notifiable): string
    {
        return 'click to reset password : ' . $this->createVerificationUrl($notifiable);
    }
}
