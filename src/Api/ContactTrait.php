<?php


namespace Bixie\Datacollectief\Api;


use GuzzleHttp\Psr7\Response as GuzzleResponse;

trait ContactTrait {

    /**
     * @param int $id
     * @return array
     * @throws DatacollectiefApiException
     */
    public function contact ($id) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'Contact', compact('id'));
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
    public function contactLimited ($id) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'ContactLimited', compact('id'));
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param int $id Company ID
     * @return array
     * @throws DatacollectiefApiException
     */
    public function contactList ($id) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'ContactList', compact('id'));
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param array $ids
     * @return array
     */
    public function multipleContacts ($ids) {
        $datas = array_map(function ($id) {
            return ['id' => $id,];
        }, $ids);
        $responses = $this->getPool('Contact', $datas);
        $results = [];
        foreach ($responses as $response) {
            if (false !== ($data = $this->getData($response))) {
                $results[] = $data;
            } else {
                //error
                $results[] = [];
            }
        }
        return $results;
    }

    /**
     * @param array $data
     * @return array
     * @throws DatacollectiefApiException
     */
    public function contactNew ($data) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'ContactNew', $data);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param array|string $contactIds
     * @param string $lastChangedDate
     * @return array
     * @throws DatacollectiefApiException
     */
    public function updatedContacts ($contactIds, $lastChangedDate = '') {
        //convert date
        $tzZ = new \DateTimeZone('Europe/Amsterdam');
        $lastChangedDate = (new \DateTime($lastChangedDate))->setTimezone($tzZ)->format('Y-m-d H:i:s');
        $contactIds = implode(',', array_unique((array)$contactIds));
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'UpdatedContacts', compact('contactIds', 'lastChangedDate'));
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param int    $contactId
     * @param int    $reasonId
     * @param array  $data
     * @param string $memo
     * @param string $otherReason
     * @return array
     * @throws DatacollectiefApiException
     */
    public function userFeedbackContact ($contactId, $reasonId, $data, $memo = '', $otherReason = '') {
        $data = array_merge(compact('contactId', 'reasonId', 'memo', 'otherReason'), $data);
        /** @var GuzzleResponse $response */
        $response = $this->send('post', 'UserFeedbackContact', $data);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

}