<?php

namespace Corals\User\Communication\database\seeds;

use Corals\Menu\Models\Menu;
use Illuminate\Database\Seeder;

class NotificationMenuTableSeeder extends Seeder
{
    public function run()
    {
        $administrationMenu = Menu::where('key', 'administration')->first();

        if ($administrationMenu) {
            // seed children menu
            $menu_items = [
                [
                    'parent_id' => $administrationMenu->id,
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
                    'parent_id' => 1,
                    'key' => 'my_notifications',
                    'url' => 'notifications',
                    'active_menu_url' => 'notifications*',
                    'name' => 'My Notifications',
                    'description' => 'Notification Menu Item',
                    'icon' => 'fa fa-bell-o',
                    'target' => null,
                    'roles' => null,
                    'order' => 0
                ],
            ];

            foreach ($menu_items as $menu_item) {
                \DB::table('menus')->updateOrInsert(['key'=>$menu_item['key']], $menu_item);
            }

        }
    }
}
