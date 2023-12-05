<?php

use Corals\Settings\Facades\Modules;
use Illuminate\Support\Facades\Route;

Route::namespace('\Corals\Foundation\Http\Controllers')
    ->controller('PublicBaseController')->group(function () {
        if (!Modules::isModuleActive('corals-cms')) {
            Route::get('/', 'welcome');
        }
    });
