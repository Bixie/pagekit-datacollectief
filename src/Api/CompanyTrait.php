<?php


namespace Bixie\Datacollectief\Api;


use GuzzleHttp\Psr7\Response as GuzzleResponse;

trait CompanyTrait {

    /**
     * @param int $id
     * @return array
     * @throws DatacollectiefApiException
     */
    public function company ($id) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'Company', compact('id'));
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws DatacollectiefApiException
     */
    public function companyLimited ($id) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'CompanyLimited', compact('id'));
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param array $options
     * @return array
     * @throws DatacollectiefApiException
     */
    public function companyList ($options) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'CompanyList', $options);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @return array
     * @throws DatacollectiefApiException
     */
    public function urlCompanySelection () {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'UrlCompanySelection');
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param int $selectionId
     * @param int $pageNr
     * @param int $recordsPerPage
     * @return array
     * @throws DatacollectiefApiException
     */
    public function companySelection ($selectionId, $pageNr = 1, $recordsPerPage = 20) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'CompanyList', compact('selectionId', 'pageNr', 'recordsPerPage'));
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param array|string $companyIds
     * @param string $lastChangedDate
     * @return array
     * @throws DatacollectiefApiException
     */
    public function updatedCompanies ($companyIds, $lastChangedDate = '') {
        //convert dates
        $tzZ = new \DateTimeZone('Europe/Amsterdam');
        $lastChangedDate = (new \DateTime($lastChangedDate))->setTimezone($tzZ)->format('Y-m-d H:i:s');
        $companyIds = implode(',', (array)$companyIds);
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'UpdatedCompanies', compact('companyIds', 'lastChangedDate'));
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param int    $companyId
     * @param int    $reasonId
     * @param array  $data
     * @param string $memo
     * @param string $otherReason
     * @return array
     * @throws DatacollectiefApiException
     */
    public function userFeedbackCompany ($companyId, $reasonId, $data, $memo = '', $otherReason = '') {
        $data = array_merge(compact('companyId', 'reasonId', 'memo', 'otherReason'), $data);
        /** @var GuzzleResponse $response */
        $response = $this->send('post', 'UserFeedbackCompany', $data);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

}