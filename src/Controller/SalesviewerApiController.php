<?php

namespace Bixie\Datacollectief\Controller;


use Bixie\Datacollectief\Event\DatacollectiefApiEvent;
use Bixie\Datacollectief\Salesviewer\SalesviewerApiException;
use Pagekit\Application as App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Salesviewer API Controller
 * @Access("datacollectief: use datacollectief")
 */
class SalesviewerApiController
{

    /**
     * @Route ("salesviewer/sessions", methods="GET", name="salesviewer/sessions")
     * @Request({"filter": "array"}, csrf=true)
     * @param array $filter
     * @return array|Response
     */
    public function sessionsAction($filter = [])
    {

        $processed_data = [];
        try {
            $sessions = App::get('salesviewer.api')->sessions($filter);
        } catch (SalesviewerApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getMessage());
        }

        foreach ($sessions['result'] as $result) {

            $event = new DatacollectiefApiEvent('datacollectief.api.salesviewer', $result);
            App::trigger($event);
            if ($data = $event->getProcessedData()) {
                $processed_data[] = $event->getProcessedData();
            }

        }

        App::config('bixie/datacollectief')->set('sv_last_checked', (new \DateTime($filter['to']))->format(DATE_ATOM));

        return compact('sessions', 'processed_data');
    }

}

