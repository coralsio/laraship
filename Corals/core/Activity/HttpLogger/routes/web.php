<?php

Route::group(['prefix' => ''], function () {
    Route::post('http-logs/purge', 'HttpLoggersController@purge');
    Route::resource('http-logs', 'HttpLoggersController')->only(['index', 'show']);
});
