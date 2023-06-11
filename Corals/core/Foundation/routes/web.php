<?php

Route::get('set-locale/{locale}', 'LanguageController@setLocale');
Route::get('datatable/i18n', 'LanguageController@datatableLanguage');
Route::post('get-model-translation', 'LanguageController@getModelTranslation');