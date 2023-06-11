<?php

namespace Corals\User\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\Group;
use Corals\User\Models\User;

class GroupPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.user';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('User::group.view')) {
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
        return $user->can('User::group.create');
    }

    /**
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function update(User $user, Group $group)
    {
        if ($user->can('User::group.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function destroy(User $user, Group $group)
    {
        if ($user->can('User::group.delete')) {
            return true;
        }
        return false;
    }


    /**
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function restore(User $user, Group $group)
    {
        if ($user->can('User::group.restore')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function hardDelete(User $user, Group $group)
    {
        if ($user->can('User::group.hardDelete')) {
            return true;
        }
        return false;
    }
}
