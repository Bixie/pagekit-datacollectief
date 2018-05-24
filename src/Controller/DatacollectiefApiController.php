<?php

namespace Bixie\Datacollectief\Controller;

use Bixie\Datacollectief\Api\DatacollectiefApiException;
use Bixie\Datacollectief\Event\DatacollectiefApiEvent;
use Pagekit\Application as App;

/**
 * Datacollectief Admin Controller
 *
 */
class DatacollectiefApiController
{

    /**
     * @Route ("/websiteleads/websites", methods="GET", name="websiteleads/websites")
     * @Request({"options": "array"}, csrf=true)
     * @return array
     */
    public function websiteleadsWebsitesAction()
    {

        try {
            $websites = App::get('datacollectief.api')->websites();
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
        }

        return compact('websites');
    }

    /**
     * @Route ("/websiteleads/leads", methods="GET", name="websiteleads/leads")
     * @Request({"options": "array"}, csrf=true)
     * @param array $options
     * @return array
     */
    public function websiteleadsLeadsAction($options = [])
    {

        try {
            $leads = App::get('datacollectief.api')->websiteleads($options);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        foreach ($leads as $lead) {
            $event = new DatacollectiefApiEvent('datacollectief.api.websitelead', $lead);
            App::trigger($event);
            $lead['processed_data'] = $event->getProcessedData();
        }

        App::config('bixie/datacollectief')->set('wl_last_checked', (new \DateTime())->format(DATE_ATOM));

        return compact('leads');
    }


}

