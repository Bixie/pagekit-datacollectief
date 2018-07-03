<?php

namespace Bixie\Datacollectief\Controller;


use Bixie\Datacollectief\Api\DatacollectiefApiException;
use Bixie\Datacollectief\Event\DatacollectiefApiEvent;
use Pagekit\Application as App;

/**
 * Datacollectief API Controller
 * @Access("datacollectief: use datacollectief")
 */
class DatacollectiefApiController
{

    /**
     * @Route ("/info", methods="GET", name="info")
     * @Request(csrf=true)
     * @return array
     */
    public function infoAction()
    {
        try {
            $versionInfo = App::get('datacollectief.api')->version();
            $downloadStatistics = App::get('datacollectief.api')->downloadStatistics();
            $accountInfo = App::get('datacollectief.api')->urlAccountInfo();

            $apiInfo = array_merge($versionInfo, $downloadStatistics, $accountInfo);

        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
        }

        return compact('apiInfo');
    }

    /**
     * @Route ("/baseTable/{table}", methods="GET", name="baseTable")
     * @Request({"table": "string", "identifier": "string"}, csrf=true)
     * @param string $table
     * @param null $identifier
     * @return array
     */
    public function baseTableAction($table, $identifier = null)
    {
        try {
            $responseData = App::get('datacollectief.api')->baseTable($table);
            if ($identifier) {
                $baseTable = [];
                foreach ($responseData[$table] as $item) {
                    $baseTable[$item[$identifier]] = $item;
                }
            } else {
                $baseTable = $responseData[$table];
            }

        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
        }

        return compact('baseTable');
    }

    /**
     * @Route ("/websiteleads/websites", methods="GET", name="websiteleads/websites")
     * @Request(csrf=true)
     * @return array
     */
    public function websiteleadsWebsitesAction()
    {

        try {
            $websiteFields = App::get('datacollectief.api')->websites();
            $Websites = $websiteFields['Websites'];
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
        }

        return compact('Websites');
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

        foreach ($leads['Visits'] as $lead) {
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

