<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;

if (!schemaHasTable('utilities_seo_items')) {
    \Schema::create('utilities_seo_items', function (Blueprint $table) {
        $table->increments('id');

        $table->string('route')->nullable()->unique()->index();
        $table->string('slug')->nullable()->unique()->index();
        $table->string('title')->nullable();
        $table->string('type')->nullable();
        $table->text('meta_keywords')->nullable();
        $table->text('meta_description')->nullable();

        $table->text('properties')->nullable();

        $table->unsignedInteger('created_by')->nullable()->index();
        $table->unsignedInteger('updated_by')->nullable()->index();

        $table->softDeletes();
        $table->timestamps();
    });
}

//SEO
\DB::table('permissions')->insert([
    [
        'name' => 'Utility::seo_item.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Utility::seo_item.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Utility::seo_item.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Utility::seo_item.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);

$utilities_menu = \DB::table('menus')->where('key', 'utility')->first();

\DB::table('menus')->insert([
    'parent_id' => $utilities_menu->id,
    'key' => null,
    'url' => config('utility.models.seo_item.resource_url'),
    'active_menu_url' => config('utility.models.seo_item.resource_url') . '*',
    'name' => 'SEO Items',
    'description' => 'SEO Items',
    'icon' => 'fa fa-search',
    'target' => null,
    'roles' => '["1"]',
    'order' => 0
]);
