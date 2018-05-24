<?php

namespace Bixie\Datacollectief;

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
        //todo main code, dependancies, boot-event
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

