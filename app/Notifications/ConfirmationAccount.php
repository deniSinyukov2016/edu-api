<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmationAccount extends Notification
{
    use Queueable;

    private $password;

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
            ->line('Ваш email был добавлен в нашу систему.')
            ->line('Пароль: ' . $this->password)
            ->line('Для подтверждения аккаунта перейдите по ссылке')
            ->action('Подтвердить аккаунт', route('confirmation-account', $notifiable->api_token))
            ->line('Если вы не делали никаких действий - просто удалите это 
                            письмо или напишите нам в службу поддержки');
    }
}
