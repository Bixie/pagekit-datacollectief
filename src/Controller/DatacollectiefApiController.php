<?php

namespace Bixie\Datacollectief\Controller;


use Bixie\Datacollectief\Api\DatacollectiefApiException;
use Bixie\Datacollectief\Event\DatacollectiefApiEvent;
use Pagekit\Application as App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Datacollectief API Controller
 * @Access("datacollectief: use datacollectief")
 */
class DatacollectiefApiController
{

    /**
     * @Route ("/info", methods="GET", name="info")
     * @Request(csrf=true)
     * @return array|Response
     */
    public function infoAction()
    {
        try {
            $versionInfo = App::get('datacollectief.api')->version();
            $downloadStatistics = App::get('datacollectief.api')->downloadStatistics();
            $accountInfo = App::get('datacollectief.api')->urlAccountInfo();

            $apiInfo = array_merge($versionInfo, $downloadStatistics, $accountInfo);

        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return compact('apiInfo');
    }

    /**
     * @Route ("/baseTable/{table}", methods="GET", name="baseTable")
     * @Request({"table": "string", "identifier": "string"}, csrf=true)
     * @param string $table
     * @param null $identifier
     * @return array|Response
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
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return compact('baseTable');
    }

    /**
     * @Route ("/company/list", methods="GET", name="company/list")
     * @Request({"options": "array"}, csrf=true)
     * @param array $options
     * @return array|Response
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
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $list;
    }

    /**
     * @Route ("/urlcompanyselection", methods="GET", name="urlcompanyselection")
     * @Request(csrf=true)
     * @return array|Response
     */
    public function urlCompanySelectionAction()
    {
        try {
            $result = App::get('datacollectief.api')->urlCompanySelection();
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/company/selection", methods="GET", name="company/selection")
     * @Request({"selectionId": "int", "pageNr": "int", "recordsPerPage": "int"}, csrf=true)
     * @param int $selectionId
     * @param int $pageNr
     * @param int $recordsPerPage
     * @return array|Response
     */
    public function companySelectionAction($selectionId, $pageNr = 1, $recordsPerPage = 20)
    {
        try {
            $list = App::get('datacollectief.api')->companySelection($selectionId, $pageNr, $recordsPerPage);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $list;
    }

    /**
     * @Route ("/company/full/{id}", methods="GET", name="company/full")
     * @Request({"id": "int"}, csrf=true)
     * @param int $id
     * @return array|Response
     */
    public function companyAction($id)
    {
        try {
            $result = App::get('datacollectief.api')->company($id);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/company/limited/{id}", methods="GET", name="company/limited")
     * @Request({"id": "int"}, csrf=true)
     * @param int $id
     * @return array|Response
     */
    public function companyLimitedAction($id)
    {
        try {
            $result = App::get('datacollectief.api')->companyLimited($id);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/company/updated", methods="GET", name="company/updated")
     * @Request({"companyIds": "array", "lastChangedDate": "string"}, csrf=true)
     * @param array  $companyIds
     * @param string $lastChangedDate
     * @return array|Response
     */
    public function updatedCompaniesAction($companyIds, $lastChangedDate = '')
    {
        try {
            $result = App::get('datacollectief.api')->UpdatedCompanies($companyIds, $lastChangedDate);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/company/feedback/{id}", methods="POST", name="company/feedback")
     * @Request({"id": "int"}, csrf=true)
     * @param int    $id
     * @param int    $reasonId
     * @param array  $data
     * @param string $memo
     * @param string $otherReason
     * @return array|Response
     */
    public function userFeedbackCompanyAction($id, $reasonId, $data, $memo = '', $otherReason = '')
    {
        try {
            $result = App::get('datacollectief.api')->userFeedbackCompany($id, $reasonId, $data, $memo, $otherReason);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/contact/list/{companyId}", methods="GET", name="contact/list")
     * @Request({"companyId": "int"}, csrf=true)
     * @param int $companyId
     * @return array|Response
     */
    public function contactListAction($companyId)
    {

        try {
            $list = App::get('datacollectief.api')->contactList($companyId);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $list;
    }

    /**
     * @Route ("/contact/full/{id}", methods="GET", name="contact/full")
     * @Request({"id": "int"}, csrf=true)
     * @param int $id
     * @return array|Response
     */
    public function contactAction($id)
    {
        try {
            $result = App::get('datacollectief.api')->contact($id);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/contact/new", methods="POST", name="contact/new")
     * @Request({"data": "array"}, csrf=true)
     * @param array $data
     * @return array|Response
     */
    public function contactNewAction($data)
    {
        try {
            $result = App::get('datacollectief.api')->contactNew($data);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/contact/limited/{id}", methods="GET", name="contact/limited")
     * @Request({"id": "int"}, csrf=true)
     * @param int $id
     * @return array|Response
     */
    public function contactLimitedAction($id)
    {
        try {
            $result = App::get('datacollectief.api')->contactLimited($id);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/contact/updated", methods="GET", name="contact/updated")
     * @Request({"contactIds": "array", "lastChangedDate": "string"}, csrf=true)
     * @param array  $contactIds
     * @param string $lastChangedDate
     * @return array|Response
     */
    public function updatedContactsAction($contactIds, $lastChangedDate = '')
    {
        try {
            $result = App::get('datacollectief.api')->UpdatedContacts($contactIds, $lastChangedDate);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/contact/feedback/{id}", methods="POST", name="contact/feedback")
     * @Request({"id": "int"}, csrf=true)
     * @param int    $id
     * @param int    $reasonId
     * @param array  $data
     * @param string $memo
     * @param string $otherReason
     * @return array|Response
     */
    public function userFeedbackContactAction($id, $reasonId, $data, $memo = '', $otherReason = '')
    {
        try {
            $result = App::get('datacollectief.api')->userFeedbackContact($id, $reasonId, $data, $memo, $otherReason);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return $result;
    }

    /**
     * @Route ("/websiteleads/websites", methods="GET", name="websiteleads/websites")
     * @Request(csrf=true)
     * @return array|Response
     */
    public function websiteleadsWebsitesAction()
    {

        try {
            $websiteFields = App::get('datacollectief.api')->websites();
            $Websites = $websiteFields['Websites'];
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        return compact('Websites');
    }

    /**
     * @Route ("/websiteleads/leads", methods="GET", name="websiteleads/leads")
     * @Request({"options": "array"}, csrf=true)
     * @param array $options
     * @return array|Response
     */
    public function websiteleadsLeadsAction($options = [])
    {
        $company_contacts = [];
        $processed_data = [];
        try {
            $leads = App::get('datacollectief.api')->websiteleads($options);
        } catch (DatacollectiefApiException $e) {
            return (new Response($e->getMessage()))->setStatusCode($e->getCode(), $e->getStatusText());
        }

        foreach ($leads['Visits'] as $lead) {
            $companyId = $lead['Company']['Id'];
            if (isset($company_contacts[$companyId])) {
                $contacts = $company_contacts[$companyId];
            } else {
                try {
                    $result = App::get('datacollectief.api')->contactList($companyId);
                    $contacts = $company_contacts[$companyId] = $result['Contacts'];
                } catch (DatacollectiefApiException $e) {
                    //todo log?
                }
            }
            $event = new DatacollectiefApiEvent('datacollectief.api.websitelead', $lead, compact('contacts'));
            App::trigger($event);
            if ($data = $event->getProcessedData()) {
                $processed_data[] = $event->getProcessedData();
            }
        }

        App::config('bixie/datacollectief')->set('wl_last_checked', (new \DateTime($options['To']))->format(DATE_ATOM));

        return compact('processed_data');
    }


}

