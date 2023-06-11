<?php

namespace Corals\User\Communication\Transformers;

use Carbon\Carbon;
use Corals\Foundation\Transformers\BaseTransformer;
use Corals\User\Communication\Models\Notification;
use Illuminate\Support\Arr;

class NotificationTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('notification.models.notification.resource_url');

        parent::__construct();
    }

    /**
     * @param Notification $notification
     * @return array
     * @throws \Throwable
     */
    public function transform(Notification $notification)
    {
        $title = $notification->data['title'];

        $title = iconv(mb_detect_encoding($title, mb_detect_order(), true), "UTF-8", $title);
        $title = addslashes($title);
        $title = htmlspecialchars($title, ENT_COMPAT, 'UTF-8');

        $notificationData = $notification->data;

        $iconLink = Arr::get($notificationData, 'icon') ?? asset(config('notification.default_notification_image'));

        $icon = sprintf("<img src='%s' alt=''>", $iconLink);
        $url = '<a href="' . url($this->resource_url . '/' . $notification->id) . '" class="modal-load" data-title="' . $title . '">';
        $transformedArray = [
            'id' => $notification->id,
            'type' => $notification->type,
            'checkbox' => $this->generateCheckboxElement($notification, $notification->id),
            'title' => $url . ($notification->read_at ? $notification->data['title'] : "<span style='font-weight:600'>" . $notification->data['title'] . "</span>") . '</a>',
            'body' => $notificationData['body'],
            'icon' => $url . $icon . '</a>',
            'created_at' => format_date($notification->created_at),
            'updated_at' => format_date($notification->updated_at),
            'read_at' => $notification->read_at ? (new Carbon($notification->read_at))->format('Y-m-d H:i') : '-',
            'action' => $this->actions($notification),
        ];

        return parent::transformResponse($transformedArray);
    }
}
