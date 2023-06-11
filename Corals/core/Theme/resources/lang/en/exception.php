<?php


return [
    'theme' => [
        'theme_exception' => 'Theme [:name] already exists',
        'theme_exception_extend' => ', Current Theme: [:theme]',
        'theme_not_found' => 'Theme :themeName not Found',

        'theme_not_added' => 'Invalid theme upload file',

        'theme_invalid_structure' => 'Invalid Theme structure',

        'theme_invalid_installed' => 'Theme :themeName already exist. You must remove it first.',

        'theme_not_exist' => 'Error: Theme :name doesn\'t exist',

        'theme_asset_not_found' => 'Asset not found [:url]',

        'theme_asset_not_found_warning' => 'Asset not found [:url] in Theme [:name]',

        'theme_import_demo' => 'No demo data found for import',

        'theme_cannot_delete' => 'Can not delete folder [:viewsPath] of theme [:name] because 
        it is also used by theme [:themeName]',

        'theme_invalid_json' => 'Invalid theme.json file [:filename]',

        'theme_invalid_cash_json' => 'Invalid theme cache json file [:cachePath]',

        'theme_invalid_folder' => 'Invalid theme.json file at [:themeFolder]',

        'theme_cannot_execute' => 'No theme is set. Can not execute method [:method] in [:self_class]',

    ],
];