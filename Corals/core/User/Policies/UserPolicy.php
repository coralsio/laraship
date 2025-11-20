<?php

namespace Corals\User\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Settings\Facades\Modules;
use Corals\User\Models\User;
use Corals\User\Models\User as UserModel;

class UserPolicy extends BasePolicy
{
    /**
     * @var array
     */
    protected $skippedAbilities = [
        'destroy',
        'update',
        'impersonate',
        'sendSMS',
        'deletedRecords',
        'records',
        'leaveImpersonation',
    ];

    /**
     * @var string
     */
    protected $administrationPermission = 'Administrations::admin.user';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('User::user.view')) {
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
        return $user->can('User::user.create');
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function update(User $user, UserModel $usermodel)
    {
        if (!isSuperUser() && isSuperUser($usermodel)) {
            return false;
        }
        $loggedInUserRoles = \Roles::getRolesListForLoggedInUser();

        $updatedUserRoles = $usermodel->roles->pluck('label', 'id');

        if ($updatedUserRoles->isNotEmpty() && $loggedInUserRoles->intersect($updatedUserRoles)->isEmpty()) {
            return false;
        }

        if ($user->can('User::user.update') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function destroy(User $user, UserModel $usermodel)
    {
        if (isSuperUser($usermodel) || $usermodel->id == $user->id) {
            return false;
        }
        $loggedInUserRoles = \Roles::getRolesListForLoggedInUser();

        $updatedUserRoles = $usermodel->roles->pluck('label', 'id');

        if ($loggedInUserRoles->intersect($updatedUserRoles)->isEmpty()) {
            return false;
        }

        if ($user->can('User::user.delete') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function restore(User $user, UserModel $usermodel)
    {
        if ($user->can('User::user.restore') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function hardDelete(User $user, UserModel $usermodel)
    {
        if ($user->can('User::user.hardDelete') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function deletedRecords(User $user, UserModel $usermodel)
    {
        if (($user->can('User::user.delete') || $user->can('User::user.restore')) && !request()->has("deleted")) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function records(User $user, UserModel $usermodel)
    {
        if ($user->can('User::user.view') && request()->has("deleted")) {
            return true;
        }
        return false;
    }

    public function impersonate(User $user, UserModel $userModel)
    {
        return (!isSuperUser($userModel) && $user->can('User::user.impersonate')) || isSuperUser($user);
    }

    /**
     * @param UserModel $user
     * @param UserModel $userModel
     * @return bool
     */
    public function sendSMS(User $user, UserModel $userModel): bool
    {
        return Modules::isModuleActive('corals-sms') && !is_null($userModel->getPhoneNumber());
    }

    public function leaveImpersonation(User $user, ?User $impersonator = null): bool
    {
        return $impersonator && $this->impersonate($impersonator, $user);
    }


}
