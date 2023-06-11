<?php

namespace Corals\User\Communication\Observers;

use Corals\User\Communication\Models\NotificationTemplate;

class NotificationTemplateObserver
{

    /**
     * @param NotificationTemplate $notification_template
     */
    public function created(NotificationTemplate $notification_template)
    {
    }
}