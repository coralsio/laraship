<?php

Breadcrumbs::register('themes', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Theme::module.theme.title'), url('themes'));
});