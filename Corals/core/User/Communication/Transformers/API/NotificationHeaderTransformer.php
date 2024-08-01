<?php

namespace Corals\User\Communication\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Illuminate\Notifications\DatabaseNotification;

class NotificationHeaderTransformer extends APIBaseTransformer
{
    /**
     * @param DatabaseNotification $notification
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function transform(DatabaseNotification $notification)
    {
        $notificationData = $notification->data;

        $transformedArray = [
            'id' => $notification->id,
            'notification_type' => data_get($notificationData, 'notification_type', 'general'),
            'title' => data_get($notificationData, 'title'),
            'img' => data_get($notificationData, 'img'),
            'read_at' => format_date($notification->read_at),
            'created_at' => ($notification->created_at)->diffForHumans()
        ];

        return parent::transformResponse($transformedArray);
    }
}
