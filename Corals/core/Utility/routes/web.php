<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'utilities'], function () {
    //gallery
    Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {
        Route::get('{hashed_id}', ['as' => 'list', 'uses' => 'Gallery\GalleryController@gallery']);
        Route::post('upload', ['as' => 'upload', 'uses' => 'Gallery\GalleryController@galleryUpload']);
        Route::post('{hashed_id}/upload', ['as' => 'upload', 'uses' => 'Gallery\GalleryController@galleryUpload']);
        Route::post('{media}/mark-as-featured',
            ['as' => 'mark-as-featured', 'uses' => 'Gallery\GalleryController@galleryItemFeatured']);
        Route::delete('{media}/delete', ['as' => 'delete', 'uses' => 'Gallery\GalleryController@galleryItemDelete']);
    });


    Route::post('newsletter/subscribe/', 'Common\PublicCommonController@subscribeNewsLetter');

    //Invite Friends
    Route::get('invite-friends', 'InviteFriends\InviteFriendsBaseController@getInviteFriendsForm');
    Route::post('invite-friends', 'InviteFriends\InviteFriendsBaseController@sendInvitation');

});
