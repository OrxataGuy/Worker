<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use App\Models\User;
use App\Models\Log;

class AppNotification extends Notification
{
    use Queueable;

    protected $message;

    public function via($notifiable) {
		return [WebPushChannel::class];
	}

	public function __construct(User $user, Log $log)
	{

        $this->message = (new WebPushMessage)
                            ->title("Notificación para $user->name")
                            ->body($log->description)
                            ->action('Visto', 'notification_action');
	}

    public function toWebPush($notifiable, $notification)
    {
        return $this->message;
	}
}
