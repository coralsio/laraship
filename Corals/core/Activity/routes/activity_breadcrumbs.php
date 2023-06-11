<?php

//Activities
Breadcrumbs::register('activities', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Activity::module.activity.title'), url('activities'));
});