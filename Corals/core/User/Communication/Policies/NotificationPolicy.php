<?php

namespace Corals\User\Communication\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Communication\Models\Notification;
use Corals\User\Models\User;

class NotificationPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.user';

    protected $skippedAbilities = ['create'];
    /**
     * @param User $user
     * @param Notification|null $notification
     * @return bool
     */
    public function view(User $user, Notification $notification = null)
    {
        if ($notification && $notification->notifiable_id != $user->id) {
            return false;
        }

        return $user->can('Notification::my_notification.view');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * @param User $user
     * @param Notification $notification
     * @return bool
     */
    public function update(User $user, Notification $notification)
    {
        if ($user->can('Notification::my_notification.update') && $notification->notifiable_id == $user->id) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Notification $notification
     * @return bool
     */
    public function destroy(User $user, Notification $notification)
    {
        if ($user->can('Notification::my_notification.delete')) {
            return true;
        }
        return false;
    }

}
