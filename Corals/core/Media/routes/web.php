<?php

Route::get('media/{media}/{target?}', 'MediasController@getMedia');

Route::delete('media/{media}', 'MediasController@mediaDelete');
