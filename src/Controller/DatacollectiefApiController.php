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
     * @Route ("/company/list", methods="GET", name="company/list")
     * @Request({"options": "array"}, csrf=true)
     * @param array $options
     * @return array
     */
    public function companyListAction($options = [])
    {
        $options = array_merge(array_fill_keys([
            'pageNr', 'recordsPerPage', 'sortOrder', 'companyName', 'street', 'streetNumber', 'zipCode',
            'city', 'telephone', 'POBox', 'POBoxZipCode', 'POBoxCity', 'url', 'email',
        ], ''), $options);

        try {
            $list = App::get('datacollectief.api')->companyList($options);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        return $list;
    }

    /**
     * @Route ("/urlcompanyselection", methods="GET", name="urlcompanyselection")
     * @Request(csrf=true)
     * @return array
     */
    public function urlCompanySelectionAction()
    {
        try {
            $result = App::get('datacollectief.api')->urlCompanySelection();
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        return $result;
    }

    /**
     * @Route ("/company/selection", methods="GET", name="company/selection")
     * @Request({"selectionId": "int", "pageNr": "int", "recordsPerPage": "int"}, csrf=true)
     * @param int $selectionId
     * @param int $pageNr
     * @param int $recordsPerPage
     * @return array
     */
    public function companySelectionAction($selectionId, $pageNr = 1, $recordsPerPage = 20)
    {
        try {
            $list = App::get('datacollectief.api')->companySelection($selectionId, $pageNr, $recordsPerPage);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        return $list;
    }

    /**
     * @Route ("/company/{id}", methods="GET", name="company")
     * @Request({"id": "int"}, csrf=true)
     * @param int $id
     * @return array
     */
    public function companyAction($id)
    {
        try {
            $result = App::get('datacollectief.api')->company($id);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        return $result;
    }

    /**
     * @Route ("/company/limited/{id}", methods="GET", name="company/limited")
     * @Request({"id": "int"}, csrf=true)
     * @param int $id
     * @return array
     */
    public function companyLimitedAction($id)
    {
        try {
            $result = App::get('datacollectief.api')->companyLimited($id);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        return $result;
    }

    /**
     * @Route ("/company/updated", methods="GET", name="company/updated")
     * @Request({"id": "int"}, csrf=true)
     * @param array  $companyIds
     * @param string $lastChangedDate
     * @return array
     */
    public function updatedCompaniesAction($companyIds, $lastChangedDate)
    {
        try {
            $result = App::get('datacollectief.api')->UpdatedCompanies($companyIds, $lastChangedDate);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        return $result;
    }

    /**
     * @Route ("/company/feedback/{id}", methods="GET", name="company/feedback")
     * @Request({"id": "int"}, csrf=true)
     * @param int    $id
     * @param int    $reasonId
     * @param array  $data
     * @param string $memo
     * @param string $otherReason
     * @return array
     */
    public function userFeedbackCompanyAction($id, $reasonId, $data, $memo = '', $otherReason = '')
    {
        try {
            $result = App::get('datacollectief.api')->userFeedbackCompany($id, $reasonId, $data, $memo, $otherReason);
        } catch (DatacollectiefApiException $e) {
            App::abort($e->getCode(), $e->getMessage());
            return [];
        }

        return $result;
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

