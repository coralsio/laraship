<?php

namespace Corals\User\Classes;

use Corals\Foundation\Facades\Filters;
use Corals\User\Models\Permission;
use Corals\User\Models\Role;

class Roles
{
    /**
     * Roles constructor.
     */
    function __construct()
    {
    }

    public function getRolesList($options = [])
    {
        $key = $options['key'] ?? 'id';

        $roles = Role::query();

        $roles = Filters::do_filter('get_roles_list_query', $roles);

//        return $roles->pluck('label', $key)->toArray();
        return $roles->pluck('label', $key);

    }

    public function getRolesListForLoggedInUser($options = [])
    {
        if (isSuperUser()) {
            return $this->getRolesList($options);
        }

        $key = $options['key'] ?? 'id';

        $roleGroupsUserCanManage = Role::query()->whereNotNull('can_manage_roles')->whereIn('id', user()->roles->pluck('id')->toArray())->pluck('can_manage_roles');

        if ($roleGroupsUserCanManage->isEmpty()) {
            return [];
        }
        // if the user have 2 roles, so we get the all roles allow for this user to manage and then get the unique roles from array in final step
        $rolesCanUserManage = [];

        foreach ($roleGroupsUserCanManage as $roleGroup) {
            $rolesCanUserManage = array_merge($rolesCanUserManage, $roleGroup);
        }

        return Role::query()->whereIn('id', array_unique($rolesCanUserManage))->pluck('label', $key);
    }


    public function getPermissionsTree()
    {
        $tree = [];

        $permissions = Permission::get();

        foreach ($permissions as $permission) {
            list($package, $model) = explode('::', $permission->name);

            list($model, $action) = explode('.', $model);

            if (!isset($tree[$package])) {
                $tree[$package] = [];
            }
            if (!isset($tree[$package][$model])) {
                $tree[$package][$model] = [];
            }
            $tree[$package][$model][$permission->id] = $action;
        }

        return $tree;
    }
}
