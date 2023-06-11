<?php

use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('http_log')) {

    /**
     * create http_log table
     */

    Schema::create('http_log', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->string('ip')->nullable()->index();
        $table->unsignedInteger('user_id')->nullable()->index();
        $table->string('email')->nullable()->index();
        $table->string('uri')->nullable()->index();
        $table->string('method')->nullable();
        $table->text('headers')->nullable();
        $table->text('body')->nullable();
        $table->text('response')->nullable();
        $table->text('files')->nullable();
        $table->text('properties')->nullable();

        $table->timestamps();
    });
}

$administration_menu = \DB::table('menus')->where('key', 'administration')->first();

$httpLogMenu = \DB::table('menus')->where('url', 'http-logs')->where('name', 'Http-Logs')->first();

if (!is_null($administration_menu) && is_null($httpLogMenu)) {
    \DB::table('menus')->insert([
        [
            'parent_id' => $administration_menu->id,
            'key' => null,
            'url' => 'http-logs',
            'active_menu_url' => 'http-logs*',
            'name' => 'Http-Logs',
            'description' => 'Http Logs Item',
            'icon' => 'fa fa-magnet',
            'target' => null, 'roles' => '["1"]',
            'order' => 999
        ]
    ]);
}

$httpLogPermissionsNames = ['Activity::http_log.view', 'Activity::http_log.delete'];

$httpLogPermissions = \DB::table('permissions')
    ->whereIn('name', $httpLogPermissionsNames)
    ->pluck('name');

$permissionsRecords = [];

foreach ($httpLogPermissionsNames as $permissionName) {
    if (!$httpLogPermissions->contains($permissionName)) {
        $permissionsRecords[] = [
            'name' => $permissionName,
            'guard_name' => config('auth.defaults.guard'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

if (filled($permissionsRecords)) {
    \DB::table('permissions')->insert($permissionsRecords);
}

update_morph_columns();