<?php

namespace Corals\User\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class UserLoginDetailsNotification extends CoralsBaseNotification
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
            'full_name' => $user->full_name,
            'email' => $user->email,
            'password' => $this->data['password'],
            'login_url' => route('login'),
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'full_name' => trans('User::attributes.user.full_name'),
            'email' => trans('User::attributes.user.email'),
            'password' => trans('User::attributes.user.password'),
            'login_url' => trans('User::labels.login_url'),
        ];
    }
}
