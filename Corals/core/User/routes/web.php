<?php

use Corals\Settings\Facades\Modules;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Route::group(['prefix' => 'profile'], function () {
    Route::get('/', 'ProfileController@index')->name('profile');
    Route::put('/', 'ProfileController@update')->name('profile-update');
});

if (Modules::isModuleActive('corals-sms')) {
    Route::get('users/{messageableHahedId}/messages', 'SMSMessagesController@show');
}

Route::group(['prefix' => ''], function () {
    Route::post('users/{user}/address', 'UserAddressesController@store');
    Route::get('users/{user}/address/{type}/edit', 'UserAddressesController@edit');
    Route::delete('users/{user}/address/{type}', 'UserAddressesController@destroy');
    Route::post('users/{user}/impersonate', 'UsersController@impersonate');
    Route::post('users/leave-impersonation', 'UsersController@leaveImpersonation')->name('impersonation.leave');
    Route::post('users/bulk-action', 'UsersController@bulkAction');
    Route::post('users/{user}/restore', 'UsersController@restore')->withTrashed();
    Route::delete('users/{user}/hard-delete', 'UsersController@hardDelete')->withTrashed();
    Route::resource('users', 'UsersController');
});

Route::group(['prefix' => ''], function () {
    Route::get('roles/get-bulk-update-modal', 'RolesController@getBulkUpdateModal');
    Route::post('roles/submit-bulk-update', 'RolesController@submitBulkUpdate');
    Route::resource('roles', 'RolesController', ['except' => ['show']]);
});

Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', 'Auth\LoginController@login');

Route::group(['prefix' => '{role_name?}'], function () {
    Route::get('login', ['as' => 'login-by-role', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('login', 'Auth\LoginController@login');
});

Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset',
    ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('password/reset/{token}',
    ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);

Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
Route::post('register', 'Auth\RegisterController@register');

Route::group(['prefix' => '{role_name?}'], function () {
    Route::get('register', ['as' => 'register-by-role', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
    Route::post('register', 'Auth\RegisterController@register');
});

Route::get('register/confirm/resend', 'Auth\RegisterController@resendConfirmation')->name('auth.resend_confirmation');
Route::get('register/confirm/{confirmation_code}', 'Auth\RegisterController@confirm')->name('auth.confirm');

Route::get('auth/token', 'Auth\TwoFactorController@showTokenForm');
Route::post('auth/token', 'Auth\TwoFactorController@validateTokenForm');
Route::post('auth/two-factor', 'Auth\TwoFactorController@setupTwoFactorAuth');

Route::get('social-auth/{provider}', 'Auth\SocialController@redirectToProvider')->name('auth.social');
Route::get('social-auth/{provider}/callback',
    'Auth\SocialController@handleProviderCallback')->name('auth.social.callback');

Route::get('import/{target}/get-import-modal', 'UserImportController@getImportModal');
Route::get('import/{target}/download-import-sample', 'UserImportController@downloadImportSample');
Route::post('import/{target}/upload-import-file', 'UserImportController@uploadImportFile');

Route::resource('groups', 'GroupsController');
Route::post('groups/{group}/restore', 'GroupsController@restore')->withTrashed();
Route::delete('groups/{group}/hard-delete', 'GroupsController@hardDelete')->withTrashed();
