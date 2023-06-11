<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Corals\Menu\Models\Menu;

if (!Schema::hasTable('countries')) {
    Schema::create('countries', function (Blueprint $table) {
        $table->increments('id');
        $table->string('code', 2)
            ->index();
        $table->string('name', 75);
    });

    $countriesTableSeeder = new \Corals\Settings\database\seeds\CountriesTableSeeder();

    $countriesTableSeeder->run();
}

if (empty(\Settings::get('address_types'))) {
    \DB::table('settings')->insert([
        'code' => 'address_types',
        'type' => 'SELECT',
        'label' => 'Address Types',
        'value' => '{"home":"Home","office":"Office","shipping":"Shipping","billing":"Billing"}',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]);
}

$administrationMenu = Menu::where('key', 'administration')->get();

$custom_fields_menu = Menu::where('url', 'custom-fields')->get();

if (!$custom_fields_menu) {
    \DB::table('menus')->create(
        [
            'parent_id' => $administrationMenu->id,// admin
            'key' => null,
            'url' => 'custom-fields',
            'active_menu_url' => 'custom-fields*',
            'name' => 'Custom Fields',
            'description' => 'Custom Fields menu Item',
            'icon' => 'fa fa-microchip',
            'target' => null, 'roles' => '["1"]',
            'order' => 985
        ]
    );
}