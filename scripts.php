<?php

return [
    'install' => function ($app) {

        $util = $app['db']->getUtility();

    },

    'uninstall' => function ($app) {

        $util = $app['db']->getUtility();

        // remove the config
        $app['config']->remove('bixie/datacollectief');
    },

    'updates' => [
    ],
];
