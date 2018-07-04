<?php


namespace Bixie\Datacollectief\Api;


use GuzzleHttp\Psr7\Response as GuzzleResponse;

trait ToolsTrait {

    /**
     * @return array
     * @throws DatacollectiefApiException
     */
    public function version () {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'Version');
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    /**
     * @return array
     * @throws DatacollectiefApiException
     */
    public function downloadStatistics () {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'DownloadStatistics');
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    /**
     * @return array
     * @throws DatacollectiefApiException
     */
    public function urlAccountInfo () {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'UrlAccountInfo');
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    /**
     * @param $table
     * @return mixed
     * @throws DatacollectiefApiException
     */
    public function baseTable ($table) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', $table);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

}