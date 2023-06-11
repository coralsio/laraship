<?php

//Add Module Admin Permission
\DB::table('permissions')->insert([
    [
        'name' => 'Administrations::admin.utility',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Utility::invite_friends.can_send_invitation',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

$member_role = \Corals\User\Models\Role::where('name', 'member')->first();

if ($member_role) {
    $member_role->givePermissionTo('Utility::invite_friends.can_send_invitation');
}

$utilities_menu = \DB::table('menus')->where([
    'parent_id' => 1,// admin
    'key' => 'utility',
])->first();

$utilities_menu_id = $utilities_menu->id;

\DB::table('menus')->insert([
    [
        'parent_id' => $utilities_menu_id,
        'key' => null,
        'url' => 'utilities/invite-friends*',
        'active_menu_url' => 'utilities/invite-friends*',
        'name' => 'Invite Friends',
        'description' => 'Invite Friends Menu Item',
        'icon' => 'fa fa-paper-plane-o',
        'target' => null,
        'roles' => '["1","2"]',
        'order' => 0
    ],
]);
