<?php

Route::group(['prefix' => 'file-manager'], function ($router) {
    $router->get('/', ['as' => 'file-manager.index', 'uses' => 'ElfinderController@showIndex']);
    $router->any('connector', ['as' => 'file-manager.connector', 'uses' => 'ElfinderController@showConnector']);
    $router->get('popup/{input_id}', ['as' => 'file-manager.popup', 'uses' => 'ElfinderController@showPopup']);
    $router->get('filepicker/{input_id}', ['as' => 'file-manager.filepicker', 'uses' => 'ElfinderController@showFilePicker']);
    $router->get('tinymce', ['as' => 'file-manager.tinymce', 'uses' => 'ElfinderController@showTinyMCE']);
    $router->get('tinymce4', ['as' => 'file-manager.tinymce4', 'uses' => 'ElfinderController@showTinyMCE4']);
    $router->get('ckeditor', ['as' => 'file-manager.ckeditor', 'uses' => 'ElfinderController@showCKeditor4']);
});
