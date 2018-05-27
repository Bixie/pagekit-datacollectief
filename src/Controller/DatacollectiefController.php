<?php

namespace Bixie\Datacollectief\Controller;

use Pagekit\Application as App;

/**
 * Datacollectief Admin Controller
 *
 * @Access (admin=true)
 */
class DatacollectiefController
{

    /**
     * @Route ("/", methods="GET", name="index")
     * @return array
     */
    public function indexAction()
    {
//        $lead = [];
//        $lead['CompanyInfo']['Id'] = 53273;
//        $event = new \Bixie\Datacollectief\Event\DatacollectiefApiEvent('datacollectief.api.websitelead', $lead);
//        App::trigger($event);
//        $lead['processed_data'] = $event->getProcessedData();

        return [
            '$view' => [
                'title' => 'Websiteleads API',
                'name' => 'bixie/datacollectief/admin/index.php',
            ],
            '$data' => [
                'config' => App::module('bixie/datacollectief')->config(),
            ],
        ];
    }

    /**
     * @Access ("system: access settings")
     * @return array
     */
    public function settingsAction()
    {
        return [
            '$view' => [
                'title' => 'Datacollectief settings',
                'name' => 'bixie/datacollectief/admin/settings.php'
            ],
            '$data' => [
                'config' => App::module('bixie/datacollectief')->config(),
                'indications' => array_values(App::taxonomy('cm.company.indication')->terms()),
            ],
        ];
    }

    /**
     * @Access ("system: access settings")
     * @Request ({"config": "array"}, csrf=true)
     * @param array $config
     * @return array
     */
    public function configAction($config = [])
    {
        App::config('bixie/datacollectief')->merge($config, true);

        return ['message' => 'success'];
    }


}

