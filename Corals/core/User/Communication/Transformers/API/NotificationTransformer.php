<?php

namespace Corals\User\Communication\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\User\Communication\Models\Notification;

class NotificationTransformer extends APIBaseTransformer
{
    /**
     * @param Notification $notification
     * @return array
     * @throws \Throwable
     */
    public function transform(Notification $notification)
    {
        $notificationData = $notification->data;

        $transformedArray = [
            'id' => $notification->id,
            'notification_type' => data_get($notificationData, 'notification_type', 'general'),
            'title' => $notificationData['title'],
            'body' => $notificationData['body'],
            'data' => $notificationData,
            'read_at' => format_date($notification->read_at),
            'created_at' => ($notification->created_at)->diffForHumans(),
            'updated_at' => format_date($notification->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
