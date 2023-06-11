<?php


\DB::table('permissions')->insert([

    [
        'name' => 'Utility::webhook.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    //Guide
    [
        'name' => 'Utility::guide.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Utility::guide.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Utility::guide.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Utility::guide.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],

    [
        'name' => 'Utility::listOfValue.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Utility::listOfValue.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Utility::listOfValue.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Utility::listOfValue.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

$tablesNeedPropertiesColumn = [
    'utility_ratings', 'utility_wishlists',
    'utility_locations', 'utility_schedules', 'utility_comments'
];

foreach ($tablesNeedPropertiesColumn as $tableName) {
    if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'properties')) {
        Schema::table($tableName, function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }
}


Schema::create('utility_guides', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');

    $table->string('url')->nullable()->unique();
    $table->string('route')->nullable()->unique();

    $table->text('properties')->nullable();

    $table->enum('status', ['active', 'inactive'])->default('active');

    $table->softDeletes();
    $table->auditable();
    $table->timestamps();

});

Schema::create('utility_list_of_values', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');

    $table->string('code')->unique();
    $table->text('value');
    $table->unsignedInteger('parent_id')->nullable();
    $table->text('properties')->nullable();
    $table->string('module')->nullable();
    $table->integer('display_order')->default(0);
    $table->enum('status', ['active', 'inactive'])->default('active');

    $table->softDeletes();
    $table->auditable();
    $table->timestamps();

    $table->foreign('parent_id')
        ->references('id')
        ->on('utility_list_of_values')
        ->onUpdate('cascade')
        ->onDelete('cascade');
});

Schema::create('utility_webhooks', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->string('event_name');
    $table->longText('payload')->nullable();
    $table->text('exception')->nullable();
    $table->string('status')->default('pending');
    $table->text('properties')->nullable();

    $table->unsignedInteger('created_by')->nullable()->index();
    $table->unsignedInteger('updated_by')->nullable()->index();

    $table->softDeletes();
    $table->timestamps();
});

$utilities_menu_id = \DB::table('menus')->where('key', 'utility')->first()->id;

\DB::table('menus')->insert([

    [
        'parent_id' => $utilities_menu_id,
        'key' => null,
        'url' => config('utility.models.guide.resource_url'),
        'active_menu_url' => config('utility.models.guide.resource_url') . '*',
        'name' => 'Guides',
        'description' => 'List Of Guides',
        'icon' => 'fa fa-list',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
    [
        'parent_id' => $utilities_menu_id,
        'key' => null,
        'url' => config('utility.models.webhook.resource_url'),
        'active_menu_url' => config('utility.models.webhook.resource_url') . '*',
        'name' => 'Webhooks',
        'description' => 'Webhooks',
        'icon' => 'fa fa-anchor',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
    [
        'parent_id' => $utilities_menu_id,
        'key' => null,
        'url' => config('utility.models.listOfValue.resource_url'),
        'active_menu_url' => config('utility.models.listOfValue.resource_url') . '*',
        'name' => 'List Of Values',
        'description' => 'List Of Values',
        'icon' => 'fa fa-list',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
]);

update_morph_columns();