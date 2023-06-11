<?php


\DB::table('permissions')->insert([

    //comment
    [
        'name' => 'Utility::comment.reply',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],


]);

$member_role = \Corals\User\Models\Role::where('name', 'member')->first();
if ($member_role) {
    $member_role->forgetCachedPermissions();
    $member_role->givePermissionTo('Utility::comment.reply');
}