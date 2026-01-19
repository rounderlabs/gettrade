<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserActivationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $verificationUrl;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $verificationUrl
     * @param $user
     */
    public function __construct($verificationUrl, $user)
    {
        $this->verificationUrl = $verificationUrl;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_activation_mail')
            ->subject('[' . config('app.name') . '] Confirm Your Email')
            ->with(['url' => $this->verificationUrl['url'], 'user' => $this->user]);
    }
}
