<?php

namespace Bixie\Datacollectief;

use Bixie\Datacollectief\Event\DatacollectiefEmailreaderListener;
use Bixie\Datacollectief\Event\WebsiteleadsListener;
use Pagekit\Application as App;
use Pagekit\Module\Module;

/**
 * Datacollectief Main Module
 */
class DatacollectiefModule extends Module
{

    /**
     * @param App $app
     * @return void
     */
    public function main(App $app)
    {

        $app->on('boot', function () use ($app) {

            $app->subscribe(
                new WebsiteleadsListener,
                new DatacollectiefEmailreaderListener
            );

            $app['datacollectief.api'] = function ($app) {
                return new Api\Api($this->config(['username', 'api_key']), $app['debug']);
            };

        });

    }

    /**
     * Whitelist of publicly accessable config keys
     *
     * @return array
     */
    public function publicConfig()
    {
        return array_intersect_key(static::config(), array_flip([]));
    }


}

