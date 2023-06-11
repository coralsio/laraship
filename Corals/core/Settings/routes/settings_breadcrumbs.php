<?php

//Settings
Breadcrumbs::register('settings', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Settings::module.setting.title'), url('settings'));
});

Breadcrumbs::register('settings_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('settings');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//CustomFieldSettings
Breadcrumbs::register('custom_field_settings', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Custom::module.custom_field.title'), url('custom-fields'));
});

Breadcrumbs::register('custom_field_settings_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('custom_field_settings');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('cache', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Cache::module.cache.title'), url('cache-management'));
});

//Modules
Breadcrumbs::register('modules', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Module::module.module.title'), url('modules'));
});