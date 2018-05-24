<?php


namespace Bixie\Datacollectief\Api;


trait WebsiteleadsTrait {

    /**
     * @return mixed
     */
    public function websites () {
        $response = $this->send('get', 'GetWebsites');
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    /**
     * @param $options (Website, From, To)
     * @return mixed
     */
    public function websiteleads ($options) {
        $response = $this->send('get', 'GetWebsites', $options);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }
}