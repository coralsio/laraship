<?php

namespace Corals\Menu\database\seeds;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    public function run()
    {
        $this->call(MenuPermissionsTableSeeder::class);

        \DB::table('menus')->delete();

        // seed root menus
        $sidebarMenuId = \DB::table('menus')->insertGetId([
                'id' => 1,
                'parent_id' => 0,
                'key' => 'sidebar',
                'url' => null,
                'name' => 'Sidebar',
                'description' => 'Sidebar Root Menu',
                'icon' => null,
                'target' => null,
                'roles' => '["1"]',
                'order' => 0
            ]
        );

        $administrationMenuId = \DB::table('menus')->insertGetId([
                'parent_id' => $sidebarMenuId,
                'key' => 'administration',
                'url' => null,
                'name' => 'Administration',
                'description' => 'Administration Root Menu',
                'icon' => 'fa fa-plug',
                'target' => null,
                'roles' => '["1"]',
                'order' => 9999
            ]
        );

        // seed children menu
        \DB::table('menus')->insert([
                [
                    'parent_id' => $sidebarMenuId,// admin
                    'key' => null,
                    'url' => 'file-manager',
                    'active_menu_url' => 'file-manager*',
                    'name' => 'File Manager',
                    'description' => 'File Manager Menu Item',
                    'icon' => 'fa fa-folder-o',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 980
                ],
                [
                    'parent_id' => $administrationMenuId,// admin
                    'key' => null,
                    'url' => 'menus',
                    'active_menu_url' => 'menu*',
                    'name' => 'Menu',
                    'description' => 'Menu Menu Item',
                    'icon' => 'fa fa-bars',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 980
                ],
                [
                    'parent_id' => $administrationMenuId,
                    'key' => 'notification_templates',
                    'url' => 'notification-templates',
                    'active_menu_url' => 'notification-templates*',
                    'name' => 'Notification Templates',
                    'description' => 'Notification Templates Menu Item',
                    'icon' => 'fa fa-bell-o',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $administrationMenuId,// admin
                    'key' => null,
                    'url' => 'custom-fields',
                    'active_menu_url' => 'custom-fields*',
                    'name' => 'Custom Fields',
                    'description' => 'Custom Fields menu Item',
                    'icon' => 'fa fa-microchip',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 985
                ],
                [
                    'parent_id' => $administrationMenuId,// admin
                    'key' => null,
                    'url' => 'settings',
                    'active_menu_url' => 'settings*',
                    'name' => 'Settings',
                    'description' => 'Settings Menu Item',
                    'icon' => 'fa fa-gears',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 990
                ],
                [
                    'parent_id' => $administrationMenuId,// admin
                    'key' => null,
                    'url' => 'activities',
                    'active_menu_url' => 'activities*',
                    'name' => 'Activities',
                    'description' => 'Activities Menu Item',
                    'icon' => 'fa fa-history',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 995
                ],
                [
                    'parent_id' => $administrationMenuId,// admin
                    'key' => null,
                    'url' => 'modules',
                    'active_menu_url' => 'modules*',
                    'name' => 'Modules',
                    'description' => 'Modules Menu Item',
                    'icon' => 'fa fa-rocket',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 999
                ],
                [
                    'parent_id' => $administrationMenuId,// admin
                    'key' => null,
                    'url' => 'themes',
                    'active_menu_url' => 'themes',
                    'name' => 'Themes',
                    'description' => 'Themes Menu Item',
                    'icon' => 'fa fa-object-group',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 999
                ],
                [
                    'parent_id' => $administrationMenuId,// admin
                    'key' => null,
                    'url' => 'cache-management',
                    'active_menu_url' => 'cache-management',
                    'name' => 'Cache Management',
                    'description' => 'Cache Management Menu Item',
                    'icon' => 'fa fa-fighter-jet',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 999
                ],
                [
                    'parent_id' => $administrationMenuId,
                    'key' => null,
                    'url' => 'http-logs',
                    'active_menu_url' => 'http-logs*',
                    'name' => 'Http-Logs',
                    'description' => 'Http Logs Item',
                    'icon' => 'fa fa-magnet',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 999
                ],
                [
                    'parent_id' => $administrationMenuId,
                    'key' => null,
                    'url' => 'translations',
                    'active_menu_url' => 'translations*',
                    'name' => 'Translations Manager',
                    'description' => 'Translations Manager',
                    'icon' => 'fa fa-language',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 999
                ],
            ]
        );

        $users_menu_id = \DB::table('menus')->insertGetId([
            'parent_id' => $sidebarMenuId,// admin
            'key' => 'user',
            'url' => null,
            'active_menu_url' => 'users*',
            'name' => 'Users',
            'description' => 'Users Menu Item',
            'icon' => 'fa fa-users',
            'target' => null,
            'roles' => '["1"]',
            'order' => 0
        ]);

        // seed users children menu
        \DB::table('menus')->insert([
                [
                    'parent_id' => $users_menu_id,
                    'key' => null,
                    'url' => 'users',
                    'active_menu_url' => 'users*',
                    'name' => 'Users',
                    'description' => 'Users List Menu Item',
                    'icon' => 'fa fa-user-o',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $users_menu_id,
                    'key' => null,
                    'url' => 'roles',
                    'active_menu_url' => 'roles*',
                    'name' => 'Roles',
                    'description' => 'Roles List Menu Item',
                    'icon' => 'fa fa-key',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],
                [
                  'parent_id' => $users_menu_id,
                  'key' => null,
                  'url' => 'groups',
                  'active_menu_url' => 'groups*',
                  'name' => 'Groups',
                  'description' => 'Groups List Menu Item',
                  'icon' => 'fa fa-users',
                  'target' => null, 'roles' => '["1"]',
                  'order' => 0
                ],
          ]
        );
    }
}
