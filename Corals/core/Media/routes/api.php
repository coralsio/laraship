<?php

use Illuminate\Support\Facades\Route;

Route::controller('APIMediaController')->group(function () {
    Route::post('get-pre-signed-url', 'getPreSignedURL');
    Route::post('store-media', 'storeMedia');
});
