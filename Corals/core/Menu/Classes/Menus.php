<?php

namespace Corals\Menu\Classes;

use Corals\Menu\Models\Menu;
use Corals\User\Models\Role;

class Menus
{
    /**
     * @param $key
     * @param null $status
     * @return array
     */
    public function getMenu($key, $status = null)
    {
        $parent_menu = Menu::where('key', $key)->active()->first();
        if ($parent_menu) {
            $menus = $parent_menu->getChildren($status);
        } else {
            $menus = [];
        }
        return $menus;
    }

    /**
     * @param $key
     * @param null $status
     * @return boolean
     */
    public function attachMenuItems($keys = [], Role $role)
    {
        $menu_items = Menu::whereIn('key', $keys)->active()->get();
        foreach ($menu_items as $menu_item) {
            $menu_item->roles = array_merge($menu_item->roles, [$role->id]);
            $menu_item->save();
        }
        return false;
    }

    public function getParents($active_key = '')
    {
        $parents = Menu::root()->active()->orderBy('order')->get();
        $pills = [];

        // set active_key if not passed
        if (empty($active_key) && $parents->count()) {
            $active_key = $parents->first()->key;
        }

        foreach ($parents as $parent) {
            array_push($pills, [
                'label' => $parent->name,
                'href' => url(config('menu.models.menu.resource_url') . 's/' . $parent->key),
                'active' => $active_key == $parent->key ? 'active' : ''
            ]);
        }

        return $pills;
    }
}
