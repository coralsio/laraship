<?php

use Carbon\Carbon;

\DB::table('permissions')->insert([
    'name' => 'Utility::content_consent.manage',
    'guard_name' => config('auth.defaults.guard'),
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

$utilities_menu = \DB::table('menus')->where([
    'parent_id' => 1,// admin
    'key' => 'utility',

])->first();

$utilities_menu_id = $utilities_menu->id;

\DB::table('menus')->insert([
    [
        'parent_id' => $utilities_menu_id,
        'key' => null,
        'url' => 'utilities/content-consent-settings',
        'active_menu_url' => 'utilities/content-consent-settings*',
        'name' => 'Content Consent',
        'description' => 'Content Consent Settings',
        'icon' => 'fa fa-gear',
        'target' => null,
        'roles' => '["1"]',
        'order' => 50
    ],
]);
