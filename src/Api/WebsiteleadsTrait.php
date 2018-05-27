<?php


namespace Bixie\Datacollectief\Api;


trait WebsiteleadsTrait {

    /**
     * http://websiteleads.datacollectief.nl/API/WebsiteLeadsAPI.svc/rest/GetWebsites?ApplicationKey=WebsiteLeads.API&LicenseName=ferd%40thefreighthero.nl&Password=FJKGDJKL654PWQ123HFJKDSHF
     * @return mixed
     */
    public function websites () {
        $response = $this->send('get', 'GetWebsites');
        if (false !== ($data = $this->getData($response))) {
            return $data ? $data['websiteField'] : [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    /**
     * @param $options (Website, From, To)
     * @return mixed
     */
    public function websiteleads ($options) {
        //convert dates
        if (!empty($options['From'])) {
            $options['From'] = (new \DateTime($options['From']))->format('Y-m-d');
        }
        if (!empty($options['To'])) {
            $options['To'] = (new \DateTime($options['To']))->format('Y-m-d');
        }
        $response = $this->send('get', 'GetWebsiteLeads', $options);
        if (false !== ($data = $this->getData($response))) {
            return $data ? $data['visitField'] : [];
        } else {
            throw new DatacollectiefApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }
}