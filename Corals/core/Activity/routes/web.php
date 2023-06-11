<?php

Route::group(['prefix' => ''], function () {
    Route::get('activities/{model_name}/{model_hashed_id}', 'ActivitiesController@showModelActivities');
    Route::post('activities/bulk-action', 'ActivitiesController@bulkAction');
    Route::resource('activities', 'ActivitiesController', ['only' => ['index', 'show', 'destroy']]);
});
