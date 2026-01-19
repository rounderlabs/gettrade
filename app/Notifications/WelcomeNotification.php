<?php

namespace App\Notifications;

use App\Mail\WelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new WelcomeMail($notifiable))
            ->to($notifiable->email);
    }

    public function viaQueues()
    {
        return [
            'mail' => 'email',
        ];
    }
}
