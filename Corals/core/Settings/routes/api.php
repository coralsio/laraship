<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'settings'], function () {
    Route::get('/', 'SettingsController@getSettings');

    Route::put('{setting}', 'SettingsController@update')->name('api.settings.update');
    Route::get('value/{code}/{default?}', 'SettingsController@getSettingValue')->name('api.settings.value');
    Route::get('active-languages', 'SettingsController@getActiveLanguages')->name('api.settings.active_languages');

    Route::get('settings-by-category/{category}',
        'SettingsController@getSettingsByCategory')
        ->name('api.settings.by_category');
});
