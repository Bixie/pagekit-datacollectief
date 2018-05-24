<?php

return [
    'name' => 'bixie/datacollectief',
    'type' => 'extension',
    'main' => 'Bixie\\Datacollectief\\DatacollectiefModule',
    'autoload' => [
        'Bixie\\Datacollectief\\' => 'src'
    ],
    'nodes' => [],
    'routes' => [
        '/datacollectief' => [
            'name' => '@datacollectief',
            'controller' => [
                'Bixie\\Datacollectief\\Controller\\DatacollectiefController'
            ]
        ],
        '/api/datacollectief' => [
            'name' => '@datacollectief/api',
            'controller' => []
        ]
    ],
    'resources' => [
        'bixie/datacollectief:' => ''
    ],
    'config' => [
        'username' => '',
        'api_key' => ''
    ],
    'menu' => [
        'datacollectief' => [
            'label' => 'Datacollectief',
            'icon' => 'packages/bixie/datacollectief/icon.svg',
            'url' => '@datacollectief/index',
            'access' => 'datacollectief: use datacollectief',
            'active' => '@datacollectief(/*)'
        ],
        'datacollectief: index' => [
            'label' => 'Datacollectief',
            'parent' => 'datacollectief',
            'url' => '@datacollectief/index',
            'access' => 'datacollectief: use datacollectief',
            'active' => '@datacollectief/index'
        ],
        'datacollectief: settings' => [
            'label' => 'Settings',
            'parent' => 'datacollectief',
            'url' => '@datacollectief/settings',
            'access' => 'system: access settings',
            'active' => '@datacollectief/settings'
        ]
    ],
    'permissions' => [
        'datacollectief: use datacollectief' => [
            'title' => 'Use datacollectief'
        ]
    ],
    'settings' => '@datacollectief/settings',
    'events' => []
];
