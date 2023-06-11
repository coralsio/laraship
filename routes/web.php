<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\Corals\Foundation\Http\Controllers')
    ->controller('PublicBaseController')->group(function () {
        Route::get('/', 'welcome');
    });
