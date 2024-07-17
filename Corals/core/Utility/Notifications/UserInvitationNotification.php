<?php

namespace Corals\Utility\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class UserInvitationNotification extends CoralsBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        return $this->data['user'] ?? [];
    }

    public function getOnDemandNotificationNotifiables()
    {
        return isset($this->data['user']) ? [] : ['mail' => $this->data['email']];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        return [
            'body' => $this->data['emailBody'],
            'subject' => $this->data['emailSubject'],
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'body' => trans('Utility::labels.notifications.invitation.body'),
            'subject' => trans('Utility::labels.notifications.invitation.subject'),
        ];
    }
}
