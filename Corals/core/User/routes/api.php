<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('login', 'LoginController@login');
    Route::post('register', 'RegisterController@register');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'LoginController@logout');
    });
});

Route::get('profile', 'ProfileController@getProfileDetails');
Route::post('profile', 'ProfileController@update');
Route::post('profile/set-password', 'ProfileController@setPassword')->name('api.profile.set_password');

Route::group([], function () {
    Route::post('users/{user}/address', 'UserAddressesController@store')->name('api.users.address.store');
    Route::delete('users/{user}/address/{type}', 'UserAddressesController@destroy')->name('api.users.address.destroy');

    Route::apiResource('users', 'UsersController', ['as' => 'api.user']);
});
