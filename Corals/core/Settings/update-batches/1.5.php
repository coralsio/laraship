<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('custom_field_settings')) {
    try {
        Schema::create('custom_field_settings', function (Blueprint $table) {
            $table->increments('id');

            $table->string('model');
            $table->string('type');
            $table->string('name');
            $table->string('label')->nullable();
            $table->boolean('required')->default(false);
            $table->text('options')->nullable();
            $table->text('custom_attributes')->nullable();
            $table->string('default_value')->nullable();
            $table->string('validation_rules')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'model']);
        });
    } catch (\Exception $exception) {
        log_exception($exception);
    }
}

if (!Schema::hasTable('custom_fields')) {
    try {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->increments('id');

            $table->string('parent_type');
            $table->unsignedInteger('parent_id');
            $table->string('field_name');

            $table->string('string_value', 255)->nullable();
            $table->double('number_value')->nullable();
            $table->text('text_value')->nullable();
            $table->text('multi_value')->nullable();
            $table->timestamp('date_value')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('field_name')->references('name')->on('custom_field_settings')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['field_name', 'parent_type', 'parent_id'], 'custom_field_parent_unique');
        });
    } catch (\Exception $exception) {
        log_exception($exception);
    }
}

try {
    $customFields = \Corals\Menu\Models\Menu::where('url', 'like', 'custom-fields')->first();

    if (!$customFields) {
        $administrationMenu = \Corals\Menu\Models\Menu::where('key', 'administration')->first();
        \Corals\Menu\Models\Menu::create([
            'parent_id' => $administrationMenu->id,// admin
            'key' => null,
            'url' => 'custom-fields',
            'active_menu_url' => 'custom-fields*',
            'name' => 'Custom Fields',
            'description' => 'Custom Fields menu Item',
            'icon' => 'fa fa-microchip',
            'target' => null, 'roles' => ["1"],
            'order' => 985
        ]);
    }

    \Corals\User\Models\Permission::updateOrCreate(['name' => 'Settings::custom_field_setting.view'], [
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]);
    \Corals\User\Models\Permission::updateOrCreate(['name' => 'Settings::custom_field_setting.create'], [
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]);
    \Corals\User\Models\Permission::updateOrCreate(['name' => 'Settings::custom_field_setting.update'], [
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]);
    \Corals\User\Models\Permission::updateOrCreate(['name' => 'Settings::custom_field_setting.delete'], [
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]);
} catch (\Exception $exception) {
    log_exception($exception);
}

\Cache::forget('schema_has_custom_field_settings');