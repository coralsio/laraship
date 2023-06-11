<?php

namespace Corals\Settings\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Settings\Models\Setting;
use Corals\User\Models\User;

class SettingPolicy extends BasePolicy
{

    protected $administrationPermission = 'Administrations::admin.setting';

    protected $skippedAbilities = [
        'update'
    ];

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Settings::setting.view')) {
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
        return $user->can('Settings::setting.create');
    }

    /**
     * @param User $user
     * @param Setting $setting
     * @return bool
     */
    public function update(User $user, Setting $setting)
    {
        if ($setting->hidden || !$setting->editable) {
            return false;
        }
        if ($user->can('Settings::setting.update') || isSuperUser($user)) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Setting $setting
     * @return bool
     */
    public function destroy(User $user, Setting $setting)
    {
        if ($user->can('Settings::setting.delete')) {
            return true;
        }
        return false;
    }

}
