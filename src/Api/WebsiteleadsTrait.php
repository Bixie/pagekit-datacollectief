<?php

namespace Bixie\Datacollectief\Api;


use GuzzleHttp\Psr7\Response as GuzzleResponse;

trait WebsiteleadsTrait {

    /**
     * @return mixed
     * @throws DatacollectiefApiException
     */
    public function websites () {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'WebsiteleadsWebsites');
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }

    /**
     * @param $options (Website, From, To)
     * @return mixed
     * @throws DatacollectiefApiException
     */
    public function websiteleads ($options) {
        //convert dates
        $tzZ = new \DateTimeZone('Europe/Amsterdam');
        if (!empty($options['From'])) {
            $options['From'] = (new \DateTime($options['From']))->setTimezone($tzZ)->format('Y-m-d');
        }
        if (!empty($options['To'])) {
            $options['To'] = (new \DateTime($options['To']))->setTimezone($tzZ)->format('Y-m-d');
        }
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'WebsiteleadsLeads', $options);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode(), $this->getErrorMessage($response));
        }
    }
}