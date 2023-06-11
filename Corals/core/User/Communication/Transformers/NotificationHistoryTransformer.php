<?php

namespace Corals\User\Communication\Transformers;


use Corals\Foundation\Transformers\BaseTransformer;
use Corals\User\Communication\Models\NotificationHistory;

class NotificationHistoryTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('notification.models.notification_template.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param NotificationHistory $notificationHistory
     * @return array
     */
    public function transform(NotificationHistory $notificationHistory)
    {
        $transformedArray = [
            'id' => $notificationHistory->id,
            'notification_name' => $notificationHistory->notification_name,
            'channels' => join(', ', $notificationHistory->channels),
            'body' => generatePopover(formatProperties($notificationHistory->body)),
            'notifiables' => generatePopover(formatProperties($notificationHistory->notifiables)),
            'created_at' => format_date_time($notificationHistory->created_at),
            'updated_at' => format_date($notificationHistory->updated_at),
        ];

        return parent::transformResponse($transformedArray);

    }
}
