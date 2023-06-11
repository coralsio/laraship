<?php

// Dashboard
Breadcrumbs::register('dashboard', function ($breadcrumbs) {
    $breadcrumbs->push(trans('User::module.dashboard.title'), url('dashboard'));
});

// Profile
Breadcrumbs::register('profile', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('User::module.profile.title'), url('profile'));
});

// Users
Breadcrumbs::register('users', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('User::module.user.title'), url('users'));
});

Breadcrumbs::register('user_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('users');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('user_show', function ($breadcrumbs) {
    $breadcrumbs->parent('users');
    $breadcrumbs->push(view()->shared('title_singular'));
});
//Groups
Breadcrumbs::register('groups', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('User::module.group.title'), url('groups'));
});

Breadcrumbs::register('group_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('groups');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('group_show', function ($breadcrumbs) {
    $breadcrumbs->parent('groups');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//
// Roles
Breadcrumbs::register('roles', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('User::module.role.title'), url('roles'));
});

Breadcrumbs::register('role_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('roles');
    $breadcrumbs->push(view()->shared('title_singular'));
});
//