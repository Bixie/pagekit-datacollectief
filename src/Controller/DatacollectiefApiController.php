<?php

namespace Bixie\Datacollectief\Controller;

use Bixie\Datacollectief\Api\DatacollectiefApiException;
use Bixie\Datacollectief\Event\DatacollectiefApiEvent;
use Pagekit\Application as App;

/**
 * Datacollectief Admin Controller
 * @Access("datacollectief: use datacollectief")
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
            $websiteFields = App::get('datacollectief.api')->websites();
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
        }

        return compact('websiteFields');
    }

    /**
     * @Route ("/websiteleads/leads", methods="GET", name="websiteleads/leads")
     * @Request({"options": "array"}, csrf=true)
     * @param array $options
     * @return array
     */
    public function websiteleadsLeadsAction($options = [])
    {

        $processed_data = [];
        try {
            $leads = App::get('datacollectief.api')->websiteleads($options);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        foreach ($leads as $lead) {
            $event = new DatacollectiefApiEvent('datacollectief.api.websitelead', $lead);
            App::trigger($event);
            if ($data = $event->getProcessedData()) {
                $processed_data[] = $event->getProcessedData();
            }
        }

        App::config('bixie/datacollectief')->set('wl_last_checked', (new \DateTime($options['To']))->format(DATE_ATOM));

        return compact('processed_data');
    }


}

