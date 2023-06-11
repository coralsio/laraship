<?php

namespace Corals\Settings\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Settings\Models\Module;
use Corals\User\Models\User;

class ModulePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.setting';

    /**
     * @param User $user
     * @param Module|null $module
     * @return bool
     */
    public function manage(User $user, Module $module = null)
    {
        if ($user->can('Settings::module.manage')) {
            return true;
        }
        return false;
    }
}
