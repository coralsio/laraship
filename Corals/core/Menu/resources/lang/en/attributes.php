<?php


return [
    'menu' => [
        'key' => 'Key',
        'name' => 'Name',
        'url' => 'URL',
        'active_menu_url' => 'Active URL pattern',
        'active_menu_url_help' => 'e.g: users* (then this menu item will be active in users/create url)',
        'icon' => 'Icon',
        'icon_help' => '<i class="fa fa-info-circle"></i> For more icons please see
                <a href="http://fontawesome.io/icons" target="_blank">
                <i class="fa fa-font-awesome" aria-hidden="true"></i> Font Awesome</a>',
        'target' => 'Target',
        'target_options' => [
            '_blank' => 'New window',
            '_self' => 'Same window',
        ],
        'roles' => 'Roles',
        'roles_help' => '<span class="text-danger">if no roles selected then menu item will be visible to all.</span>',
        'description' => 'Description',
        'always_active' => 'Always Active'
    ]

];