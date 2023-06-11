<?php

//notification_template
Breadcrumbs::register('notification_template', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Notification::module.notification_template.title'), url(config('notification.models.notification_template.resource_url')));
});

Breadcrumbs::register('notification_template_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('notification_template');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('notification', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Notification::module.notification.title'), url(config('notification.models.notification.resource_url')));
});
