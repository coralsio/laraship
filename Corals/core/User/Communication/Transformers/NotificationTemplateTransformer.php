<?php

namespace Corals\User\Communication\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\User\Communication\Models\NotificationTemplate;

class NotificationTemplateTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('notification.models.notification_template.resource_url');

        parent::__construct();
    }

    /**
     * @param NotificationTemplate $notification_template
     * @return array
     * @throws \Throwable
     */
    public function transform(NotificationTemplate $notification_template)
    {
        $transformedArray = [
            'id' => $notification_template->id,
            'friendly_name' => '<a href="' . $notification_template->getShowURL() . '" target="_blank">' . $notification_template->friendly_name . '</a>',
            'name' => $notification_template->name,
            'title' => $notification_template->title,
            'body' => json_encode($notification_template->body),
            'checkbox' => $this->generateCheckboxElement($notification_template),
            'status' => formatStatusAsLabels($notification_template->status),
            'extras' => json_encode($notification_template->extras),
            'created_at' => format_date($notification_template->created_at),
            'updated_at' => format_date($notification_template->updated_at),
            'action' => $this->actions($notification_template),
        ];

        return parent::transformResponse($transformedArray);
    }
}