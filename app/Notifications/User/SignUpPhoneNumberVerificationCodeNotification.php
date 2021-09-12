<?php

namespace App\Notifications\User;

use App\Channels\OurSmsChannel;
use App\Channels\OurSmsNotificationContract;
use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class SignUpPhoneNumberVerificationCodeNotification extends Notification implements WhatsappMessageNotificationContract, OurSmsNotificationContract
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [OurSmsChannel::class, WhatsappMessageChannel::class];
    }

    public function toOurSms($notifiable): string
    {
        return 'click to verify : ' . $this->createVerificationUrl($notifiable);
    }

    public function toWhatsappMessage($notifiable): string
    {
        return 'click to verify : ' . $this->createVerificationUrl($notifiable);
    }

    private function createVerificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'web.sign_up.verify',
            Carbon::now()->addMinutes(15),
            [
                'id' => $notifiable->getKey(),
                'verification_code' => $notifiable->verification_code,
                'hash' => sha1($notifiable->phone_number),
            ]
        );

    }
}
