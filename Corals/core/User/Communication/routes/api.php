<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.'], function () {
    Route::resource('notifications', 'NotificationController')->only('index');
});

Route::get('notifications/un-read', 'NotificationController@getUnReadNotificationsByUser');

Route::post('notifications/{notification}/toggle-read-at', 'NotificationController@toggleReadAt');

Route::post('notifications/mark-all-as-read', 'NotificationController@markAllAsRead');
