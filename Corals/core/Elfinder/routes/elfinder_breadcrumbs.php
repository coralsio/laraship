<?php

//Menu
Breadcrumbs::register('file-manager', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('elfinder::module.elfinder.title'), url(config('elfinder.resource_url')));
});