<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;
use Illuminate\Notifications\Notification;

class TaskFinished extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return [PusherChannel::class];
    }

    public function toPushNotification($notifiable)
    {
        return PusherMessage::create()
            ->web()
            ->title('Tarea finalizada')
            ->sound('success')
            ->body("La tarea se ha finalizado correctamente");
    }
}
