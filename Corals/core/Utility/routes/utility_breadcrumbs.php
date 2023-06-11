<?php

Breadcrumbs::register('utility_invite_friends_create', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(view()->shared('title'));
});

