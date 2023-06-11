<?php

namespace Corals\User\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;
use Corals\User\Mail\UserRegistered;

class UserRegisteredNotification extends CoralsBaseNotification
{
    /**
     * @param null $subject
     * @param null $body
     * @return UserRegistered|null
     */
    protected function mailable($subject = null, $body = null)
    {
        return new UserRegistered($this->data['user'], $subject, $body);
    }

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
            'dashboard_link' => url('dashboard')
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'name' => trans('User::labels.notification.parameters_description_name'),
            'dashboard_link' => trans('User::labels.notification.parameters_description_link'),
        ];
    }
}
