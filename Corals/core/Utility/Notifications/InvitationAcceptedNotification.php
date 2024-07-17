<?php

namespace Corals\Utility\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class InvitationAcceptedNotification extends CoralsBaseNotification
{

    public function getNotifiables()
    {
        return $this->data['inviter'];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $invitee = $this->data['invitee'];

        return [
            'invitee_name' => $invitee->full_name,
            'invitee_email' => $invitee->email,
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'invitee_name' => trans('Utility::notifications.accept_invitation.invitee_name'),
            'invitee_email' => trans('Utility::notifications.accept_invitation.invitee_email')
        ];
    }
}
