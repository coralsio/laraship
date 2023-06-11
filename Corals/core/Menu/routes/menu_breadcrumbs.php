<?php

//Menu
Breadcrumbs::register('menu', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Menu::module.menu.title'), url(config('menu.models.menu.resource_url')));
});