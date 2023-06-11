<?php


return [
    'setting' => [
        'label' => 'Label',
        'value' => 'Value',
        'code' => 'Code',
        'category' => 'Category',
        'enable' => 'Enabled',
    ],
    'custom_field' => [
        'model' => 'Model',
        'name' => 'Field Name',
        'label' => 'Label',
        'type' => 'Type',
        'type_options' => [
            'text' => 'Text',
            'textarea' => 'TextArea',
            'date' => 'Date',
            'number' => 'Number',
            'select' => 'Select',
            'checkbox' => 'CheckBox',
            'radio' => 'Radio',
            'multi_values' => 'Multi Values',
            'label' => 'Label',
            'file' => 'File',
            'hidden' => 'Hidden',
            'google_location' => 'Google location',
            'color' => 'Color'
        ],

        'select_display_type_options' => [
            'label' => 'Label',
            'color' => 'Color',
            'image' => 'Image'
        ],

        'field_config' => [
            'searchable' => 'Searchable',
            'sortable' => 'Sortable',
            'full_text_search' => 'Full text search',
            'show_in_list' => 'Show in list',
            'grid_class' => 'Grid class',
            'order' => 'Order',
            'full_text_search_options' => [
                'title' => 'Title',
                'content' => 'Content'
            ],
            'is_identifier' => 'Is Identifier'
        ],
        'default_value' => 'Default Value',
        'validation_rules' => 'Validation Rules',
        'validation_rules_help' => '<a target="_blank" href="https://laravel.com/docs/7.x/validation" >Laravel Validation Rules</a>',
        'attribute' => 'Input Attributes <small>(placeholder, title etc...)</small>',
        'options_source' => 'Options Source',
        'options_source_model' => 'Source Model',
        'options_source_model_column' => 'Show Model Column',
        'options' => 'Options',
        'required' => 'Required'
    ],
    'module' => [
        'code' => 'Module Code',
        'license' => 'Module License Key',
    ],
    'address' => [
        'type' => 'Type',
        'address_one' => 'Address 1',
        'address_two' => 'Address 2',
        'city' => 'City',
        'state' => 'State',
        'zip' => 'Zip',
        'country' => 'Country',
        'action' => 'Action',
    ],
];
