<?php

namespace Corals\User\Communication\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Communication\Models\NotificationTemplate;
use Corals\User\Models\User;

class NotificationTemplatePolicy extends BasePolicy
{

    protected $administrationPermission = 'Administrations::admin.user';

    protected $skippedAbilities = ['create'];
    /**
     * @param User $user
     * @param NotificationTemplate|null $notification_template
     * @return bool
     */
    public function view(User $user, NotificationTemplate $notification_template = null)
    {
        if ($user->can('Notification::notification_template.view')) {
            return true;
        }
        return false;
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
     * @param NotificationTemplate $notification_template
     * @return bool
     */
    public function update(User $user, NotificationTemplate $notification_template)
    {
        if ($user->can('Notification::notification_template.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param NotificationTemplate $notification_template
     * @return bool
     */
    public function destroy(User $user, NotificationTemplate $notification_template)
    {
        if ($user->can('Notification::notification_template.delete')) {
            return true;
        }
        return false;
    }
}
