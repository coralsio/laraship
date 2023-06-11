<?php

namespace Corals\User\Communication\Traits;


use Corals\User\Communication\Models\NotificationHistory;

trait HasNotificationHistory
{
    /**
     * @return mixed
     */
    public function notificationHistory()
    {
        return $this->morphMany(NotificationHistory::class, 'model')->latest();
    }

    /**
     * @param $notificationName
     * @return mixed
     */
    public function notificationHistoryFor($notificationName)
    {
        return $this->notificationHistory()->for($notificationName)->get();
    }
}