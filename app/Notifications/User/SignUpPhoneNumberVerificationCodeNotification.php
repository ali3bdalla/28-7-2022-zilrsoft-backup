<?php

namespace App\Notifications\User;

use App\Channels\OurSmsChannel;
use App\Channels\OurSmsNotificationContract;
use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class SignUpPhoneNumberVerificationCodeNotification extends Notification implements WhatsappMessageNotificationContract, OurSmsNotificationContract, ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [OurSmsChannel::class, WhatsappMessageChannel::class];
    }

    public function toOurSms($notifiable): string
    {
        return 'رابط تاكيد التسجيل : ' . $this->createVerificationUrl($notifiable);
    }

    private function createVerificationUrl($notifiable): string
    {
        return shortLink(URL::temporarySignedRoute(
            'web.sign_up.verify',
            Carbon::now()->addMinutes(15),
            [
                'id' => $notifiable->getKey(),
                'verification_code' => $notifiable->verification_code,
                'hash' => sha1($notifiable->phone_number),
            ]
        ));

    }

    public function toWhatsappMessage($notifiable): string
    {
        return 'رابط تاكيد الحساب : ' . $this->createVerificationUrl($notifiable);
    }
}
