<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function build()
    {
        $address = 'info@msbrshop.com';
        $subject = 'This is a demo!';
        $name = 'Jane Doe';

        return $this->view('emails.test')
            ->from($address, $name)
            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo("no-reply@msbrshop.com", $name)
            ->subject($subject)
            ->with(['test_message' => ""]);
    }
}
