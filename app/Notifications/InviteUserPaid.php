<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InviteUserPaid extends Notification
{
    use Queueable;

    protected $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You was add into our system')
            ->line('Login using your email and password')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->password)
            ->action('Go to site', url('/'))
            ->line('Thank you for using our application!');
    }
}
