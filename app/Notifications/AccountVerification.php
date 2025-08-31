<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountVerification extends Notification
{
    protected $url;
    protected $account;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->markdown('verify', [
                'actionUrl' => $this->url,
                'account' => $notifiable, // Pass the account model
            ]);
    }
}
