<?php

Route::get('menus/{key?}', 'MenuController@index');

Route::group(['prefix' => ''], function () {
    Route::post('menu/update-tree/{menu}', 'MenuController@updateTree');
    Route::post('menu/{menu}/toggle-status', 'MenuController@toggleStatus');
    Route::resource('menu', 'MenuController', ['except' => 'index', 'show']);
});
