<?php

namespace Corals\User\Communication\Observers;

use Corals\User\Communication\Events\CoralsBroadcastEvent;
use Corals\User\Communication\Models\Notification;
use Illuminate\Notifications\DatabaseNotification;

class NotificationObserver
{

    /**
     * @param Notification $notification
     */
    public function created(DatabaseNotification $notification)
    {
        $notification = Notification::find($notification->id);
        
        $notifiable = $notification->notifiable;

        $channelName = sprintf("%s.%s", strtolower(class_basename($notifiable)), $notifiable->hashed_id);

        event(new CoralsBroadcastEvent($channelName, $notification, $notifiable));
    }
}
