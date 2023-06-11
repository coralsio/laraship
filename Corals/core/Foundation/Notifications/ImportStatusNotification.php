<?php

namespace Corals\Foundation\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class ImportStatusNotification extends CoralsBaseNotification
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
            'user_name' => $user->full_name,
            'user_email' => $user->email,
            'import_file_name' => data_get($this->data, 'import_file_name'),
            'import_log_file' => data_get($this->data, 'import_log_file'),
            'success_records_count' => data_get($this->data, 'success_records_count'),
            'failed_records_count' => data_get($this->data, 'failed_records_count'),
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'user_name' => 'User Name',
            'user_email' => 'User Email',
            'import_file_name' => 'Import file name',
            'import_log_file' => 'Import log file link in case of failure',
            'success_records_count' => 'Success records count',
            'failed_records_count' => 'Failed records count',
        ];
    }
}
