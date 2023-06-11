<?php

namespace Corals\User\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class UserConfirmationNotification extends CoralsBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        return $this->data['user'];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $user = $this->data['user'];

        return [
            'name' => $user->full_name,
            'confirmation_link' => url('register/confirm/' . $user->confirmation_code)
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'name' => trans('User::labels.confirmation.notification_parameters.name'),
            'confirmation_link' => trans('User::labels.confirmation.notification_parameters.link'),
        ];
    }
}
