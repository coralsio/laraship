<?php

return [
    'models' => [
        'menu' => [
            'presenter' => \Corals\Menu\Transformers\MenuPresenter::class,
            'resource_url' => 'menu',
            'translatable' => ['name'],
            'htmlentitiesExcluded' => [],
            'actions' => [
                'create' => [
                    'icon' => 'fa fa-fw fa-plus',
                    'href_pattern' => ['pattern' => '[arg]/create?parent=[arg]',
                        'replace' => [
                            'return config("menu.models.menu.resource_url");',
                            'return $object->hashed_id;'
                        ]
                    ],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ['return trans("Menu::labels.create_sub");']],
                    'data' => [
                        'action' => 'load',
                        'load_to' => '#menu_form'
                    ]
                ],
                'edit' => [
                    'href_pattern' => ['pattern' => '[arg]/edit', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ['return trans("Corals::labels.edit");']],
                    'data' => [
                        'action' => 'load',
                        'load_to' => '#menu_form'
                    ]
                ],
                'delete' => [
                    'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ['return trans("Corals::labels.delete");']],
                    'data' => [
                        'action' => 'delete',
                        'page_action' => 'site_reload'
                    ]
                ],
                'toggle' => [
                    'icon' => 'fa fa-fw fa-flag-o',
                    'href_pattern' => ['pattern' => '[arg]/toggle-status', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ['return trans("Menu::labels.toggle_status");']],
                    'data' => [
                        'action' => 'post',
                        'page_action' => 'site_reload'
                    ]
                ],
            ],
        ],
    ]
];