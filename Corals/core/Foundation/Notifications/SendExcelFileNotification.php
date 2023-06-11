<?php

namespace Corals\Foundation\Notifications;


use Corals\User\Communication\Classes\CoralsBaseNotification;

class SendExcelFileNotification extends CoralsBaseNotification
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
            'name' => $user->name,
            'table_id' => $this->data['table_id'],
            'email' => $user->email
        ];
    }

    protected function getAttachments()
    {
        return [
            $this->data['file']
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'name' => 'User Name',
            'table_id' => 'Table ID',
            'email' => 'Email'
        ];
    }
}
