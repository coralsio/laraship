<?php

namespace Corals\Foundation\Policies;

class BasePolicy
{
    /**
     * @var array
     */
    protected $skippedAbilities = [];

    /**
     * @var string
     */
    protected $administrationPermission = '';

    /**
     * @param $user
     * @param $ability
     * @return bool
     */


    public function __call($name, $arguments)
    {
        return false;
    }

    public function before($user, $ability)
    {
        if (in_array($ability, $this->skippedAbilities)) {
            return null;
        }

        return $this->isAdministrator($user);
    }

    public function admin($user, $model = null)
    {
        if (empty($this->administrationPermission)) {
            return isSuperUser($user);
        }

        return $user->hasPermissionTo($this->administrationPermission) || isSuperUser($user);
    }

    protected function isAdministrator($user)
    {
        if ((!empty($this->administrationPermission) && $user->hasPermissionTo($this->administrationPermission)) || isSuperUser($user)) {
            return true;
        }

        return null;
    }
}
