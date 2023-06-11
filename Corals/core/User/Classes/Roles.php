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

        return $roles->pluck('label', $key)->toArray();
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
