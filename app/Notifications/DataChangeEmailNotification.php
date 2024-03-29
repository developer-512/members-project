<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DataChangeEmailNotification extends Notification
{
    use Queueable;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return $this->getMessage();
    }

    public function getMessage()
    {
        
        return (new MailMessage())
            ->subject(config('app.name') . ': entry ' . $this->data['action'] . ' in ' . $this->data['model_name'])
            ->greeting('Hi,')
            ->line('We willen u laten weten dat de in ' . $this->data['model_name'].' is '. $this->data['action'] )
            ->line('Logt u in om meer informatie te zien')
            ->action(config('app.name'), config('app.url'))
            ->line('Thank you')
            ->line(config('app.name') . ' Team')
            ->salutation(' ');
    }
}
