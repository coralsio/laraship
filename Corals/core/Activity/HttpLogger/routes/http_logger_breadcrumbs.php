<?php

//HttpLogs
Breadcrumbs::register('http_log', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Http Log', url('http-logs'));
});

Breadcrumbs::register('http_log_show', function ($breadcrumbs, $title_singular) {
    $breadcrumbs->parent('http_log');
    $breadcrumbs->push($title_singular);
});

