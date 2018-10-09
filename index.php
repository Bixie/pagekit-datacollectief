<?php

return [
    'name' => 'bixie/datacollectief',

    'type' => 'extension',

    'main' => 'Bixie\\Datacollectief\\DatacollectiefModule',

    'autoload' => [
        'Bixie\\Datacollectief\\' => 'src',
    ],

    'nodes' => [],

    'routes' => [
        '/datacollectief' => [
            'name' => '@datacollectief',
            'controller' => [
                'Bixie\\Datacollectief\\Controller\\DatacollectiefController',
            ],
        ],
        '/api/datacollectief' => [
            'name' => '@datacollectief/api',
            'controller' => [
                'Bixie\\Datacollectief\\Controller\\DatacollectiefApiController',
                'Bixie\\Datacollectief\\Controller\\SalesviewerApiController',
            ],
        ],
    ],

    'resources' => [
        'bixie/datacollectief:' => '',
    ],

    'config' => [
        'api_url' => 'https://api.datacollectief.nl/api/',
        'application_name' => '',
        'user' => '',
        'password' => '',
        'BaseTableFunction' => [],
        'BaseTableBranche' => [],
        'BaseTableEmployee' => [],
        'BaseTableImportExport' => [],
        'BaseTableLegalForm' => [],
        'BaseTableMessageReason' => [],
        'wl_last_checked' => (new DateTime())->format(DATE_ATOM),
        'wl_import_functions' => [],
        'wl_tag_ignore' => [],
        'wl_tag_add' => [],
        'wl_tag_remove' => [],
        'sv_api_key' => '',
        'sv_last_checked' => (new DateTime())->format(DATE_ATOM),
    ],

    'menu' => [
        'datacollectief' => [
            'label' => 'Datacollectief',
            'icon' => 'packages/bixie/datacollectief/icon.svg',
            'url' => '@datacollectief/index',
            'access' => 'datacollectief: use datacollectief',
            'active' => '@datacollectief(/*)',
        ],
        'datacollectief: index' => [
            'label' => 'Websiteleads',
            'parent' => 'datacollectief',
            'url' => '@datacollectief/index',
            'access' => 'datacollectief: use datacollectief',
            'active' => '@datacollectief/index',
        ],
        'datacollectief: salesviewer' => [
            'label' => 'Salesviewer',
            'parent' => 'datacollectief',
            'url' => '@datacollectief/salesviewer',
            'access' => 'datacollectief: use datacollectief',
            'active' => '@datacollectief/salesviewer',
        ],
        'datacollectief: settings' => [
            'label' => 'Settings',
            'parent' => 'datacollectief',
            'url' => '@datacollectief/settings',
            'access' => 'system: access settings',
            'active' => '@datacollectief/settings',
        ]
    ],

    'permissions' => [
        'datacollectief: use datacollectief' => [
            'title' => 'Use datacollectief',
        ]
    ],

    'settings' => '@datacollectief/settings',

    'events' => [],
];
