<?php

namespace Corals\Utility\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilityMenuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $utilities_menu_id = \DB::table('menus')->insertGetId([
            'parent_id' => 1,// admin
            'key' => 'utility',
            'url' => null,
            'active_menu_url' => 'utilities*',
            'name' => 'Utilities',
            'description' => 'Utilities Menu Item',
            'icon' => 'fa fa-cloud',
            'target' => null, 'roles' => '["1"]',
            'order' => 0
        ]);

        $menuInvitationData = [
            'parent_id' => $utilities_menu_id,
            'key' => 'invite_friends_menu',
            'url' => config('utility.models.invite_friends.resource_url'),
            'active_menu_url' => config('utility.models.invite_friends.resource_url') . '*',
            'name' => 'Invite Friends',
            'description' => 'Invite Friends Menu Item',
            'icon' => 'fa fa-paper-plane-o',
            'target' => null,
            'roles' => '["1","2"]',
            'order' => 0
        ];

        DB::table('menus')->insert(
            $menuInvitationData
        );
    }
}
