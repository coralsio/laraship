<?php

namespace Corals\User\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\User\Models\Role;

class RolePolicy extends BasePolicy
{
    protected $skippedAbilities = [
        'destroy', 'update'
    ];

    protected $administrationPermission = 'Administrations::admin.user';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('User::role.view')) {
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
        return $user->can('User::role.create');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function update(User $user, Role $role)
    {
        $super_user_role = \Settings::get('super_user_role_id', 1);

        if ($role->id == $super_user_role) {
            return false;
        }

        if ($user->can('User::role.update') || isSuperUser($user)) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function destroy(User $user, Role $role)
    {
        $super_user_role = \Settings::get('super_user_role_id', 1);

        if ($role->id == $super_user_role) {
            return false;
        }

        if ($user->can('User::role.delete') || isSuperUser($user)) {
            return true;
        }
        return false;
    }

}
