<?php

Route::get('utilities/select2', 'UtilitiesController@select2');
Route::get('utilities/select2-public', 'UtilitiesController@select2Public');

Route::get('utilities/render-query-builder-filter-input', 'UtilitiesController@renderQueryBuilderFilterInput');

Route::resource('settings', 'SettingsController');

Route::resource('custom-fields', 'CustomFieldSettingsController', [
    'parameters' => ['custom-fields' => 'customFieldSetting'],
    'except' => ['show']
]);
Route::post('settings/update-settings-order', 'SettingsController@updateSettingsOrder');

Route::get('settings/download/{setting}', 'SettingsController@fileDownload');
Route::get('customer-fields/get-form', 'CustomFieldSettingsController@getForm');

Route::group(['prefix' => 'modules'], function () {
    Route::get('/', 'ModulesController@index');
    Route::get('/rescan', 'ModulesController@index');
    Route::post('{module}/install', 'ModulesController@install');
    Route::post('{module}/uninstall', 'ModulesController@uninstall');
    Route::post('{module}/update', 'ModulesController@update');
    Route::post('{module}/download', 'ModulesController@downloadRemote');
    Route::post('{module}/{status}', 'ModulesController@toggleStatus');

    Route::get('/add', 'ModulesController@add');

    Route::post('/add', 'ModulesController@downloadNew');

    Route::get('{module}/license-key', 'ModulesController@licenseKey');
    Route::put('{module}/license-key', 'ModulesController@saveLicenseKey');
});

Route::get('cache-management', 'SettingsController@cacheIndex');
Route::post('cache-management/{action}', 'SettingsController@cacheAction');
