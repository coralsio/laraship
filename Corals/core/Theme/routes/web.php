<?php

Route::group(['prefix' => 'themes'], function () {
    Route::get('add', 'ThemesController@addModal');
    Route::post('activate/{type}/{name}', 'ThemesController@activateTheme');
    Route::post('deactivate/{type}/{name}', 'ThemesController@deactivateTheme');
    Route::post('import-demo/{name}', 'ThemesController@importDemo');
    Route::post('download-update/{name}', 'ThemesController@downloadUpdate');
    Route::delete('uninstall/{name}', 'ThemesController@uninstallTheme');
    Route::post('add', 'ThemesController@addTheme');
    Route::get('/', 'ThemesController@index');
});