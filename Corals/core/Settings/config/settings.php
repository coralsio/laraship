<?php

return [
    // valid mimes for settings of type file
    'mimes' => 'jpg,jpeg,png,txt,csv,pdf',
    'types' => [
        'TEXT' => 'Text',
        'TEXTAREA' => 'Text Area',
        'BOOLEAN' => 'Boolean',
        'NUMBER' => 'Number',
        'DATE' => 'Date',
        'SELECT' => 'Select Options',
        'FILE' => 'File'
    ],
    'upload_path' => 'uploads/settings',
    'models' => [
        'setting' => [
            'presenter' => \Corals\Settings\Transformers\SettingPresenter::class,
            'resource_url' => 'settings',
            'actions' => [
                'delete' => [],
                'edit' => [
                    'icon' => '',
                    'href_pattern' => ['pattern' => '[arg]/edit', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ['return trans("Corals::labels.edit");']],
                    'policies' => ['update'],
                    'data' => [
                        'action' => 'modal-load',
                        'title' => 'Edit'
                    ]
                ]
            ]
        ],
        'country' => [],
        'module' => [
            'resource_url' => 'modules',
            'updater_url' => env('UPDATER_URL', 'https://manage.laraship.com/plugin-updater'),
            'disable_update' => env('DISABLE_UPDATE', false),
            'license_key' => env('LICENSE_KEY', ''),
            'tmp_path' => env('TEMP_PATH', sys_get_temp_dir()),
            'paths' => [
                'module' => 'Corals/modules',
                'core' => 'Corals/core',
                'payment' => 'Corals/modules/Payment',
            ]
        ],
        'custom_field_setting' => [
            'resource_url' => 'custom-fields',
            'presenter' => \Corals\Settings\Transformers\CustomFieldSettingPresenter::class,
            'supported_types' => [
                'text' => 'Settings::attributes.custom_field.type_options.text',
//                'color' => 'Settings::attributes.custom_field.type_options.color',
                'google_location' => 'Settings::attributes.custom_field.type_options.google_location',
                'textarea' => 'Settings::attributes.custom_field.type_options.textarea',
                'date' => 'Settings::attributes.custom_field.type_options.date',
                'number' => 'Settings::attributes.custom_field.type_options.number',
                'select' => 'Settings::attributes.custom_field.type_options.select',
                'checkbox' => 'Settings::attributes.custom_field.type_options.checkbox',
//                'radio' => 'Settings::attributes.custom_field.type_options.radio',
                'multi_values' => 'Settings::attributes.custom_field.type_options.multi_values',
                'label' => 'Settings::attributes.custom_field.type_options.label',
                'file' => 'Settings::attributes.custom_field.type_options.file',
                'hidden' => 'Settings::attributes.custom_field.type_options.hidden',
            ],
            'select_display_type_options' => [
                'label' => 'Settings::attributes.custom_field.select_display_type_options.label',
                'color' => 'Settings::attributes.custom_field.select_display_type_options.color',
                'image' => 'Settings::attributes.custom_field.select_display_type_options.image'
            ],

            'translatable' => ['label']
        ]
    ],
    'supported_commands' => [
        'route:cache' => [
            'text' => 'Create a route cache file for faster route registration.',
            'class' => 'btn-success'
        ],
        'config:cache' => [
            'text' => 'Create a cache file for faster configuration loading.',
            'class' => 'btn-success'
        ],
        'theme:refresh-cache' => [
            'text' => 'Rebuilds the cache of "theme.json" files for each theme.',
            'class' => 'btn-success'
        ],
        'modelCache:flush' => [
            'text' => 'Flush model caching, includes menus, settings and custom fields',
            'class' => 'btn-warning'
        ],
        'cache:clear' => [
            'text' => 'Flush the application cache.',
            'class' => 'btn-warning'
        ],
        'config:clear' => [
            'text' => 'Remove the configuration cache file.',
            'class' => 'btn-warning'
        ],
        'auth:clear-resets' => [
            'text' => 'Flush expired password reset tokens.',
            'class' => 'btn-warning'
        ],
        'activitylog:clean' => [
            'text' => 'Clean up old records from the activity log.',
            'class' => 'btn-warning'
        ],
        'view:clear' => [
            'text' => 'Clear all compiled view files.',
            'class' => 'btn-warning'
        ],
        'route:clear' => [
            'text' => 'Remove the route cache file.',
            'class' => 'btn-warning'
        ],
//        'queue:flush' => [
//            'text' => '',
//            'class' => 'btn-warning'
//        ],
        'media-library:clean' => [
            'text' => 'Clean deprecated conversions and files without related model.',
            'class' => 'btn-warning'
        ],
//        'currency:cleanup' => [
//            'text' => '',
//            'class' => 'btn-warning'
//        ],
        'clear-compiled' => [
            'text' => 'Remove the compiled class file.',
            'class' => 'btn-warning'
        ]
    ],
];
