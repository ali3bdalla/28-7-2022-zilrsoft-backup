<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Lang;
class SendWelcomeMessageAfterVerfityingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {

        $this->user = $user;
        //
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // get offers and shared with the user

        return $this->subject(Lang::getFromJson('Verify Email Address'))->markdown('vendor.mail.welcome');


    }
}
