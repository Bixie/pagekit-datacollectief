<?php

namespace Bixie\Datacollectief\Salesviewer;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class Api {


    protected $config = [];

    protected $debug = false;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Api constructor.
     * @param array $config
     * @param bool  $debug
     */
    public function __construct (array $config, $debug) {
        $this->config = array_merge(array_fill_keys(['sv_api_key',], ''), $config);
        $this->debug = $debug;

        $this->client = new Client(['base_uri' => 'https://www.salesviewer.com/api/', 'verify' => !$this->debug]);
    }

    /**
     * @param array $filter
     * @return array
     */
    public function sessions ($filter = []) {
        /** @var GuzzleResponse $response */
        $response = $this->send('get', 'sessions', $filter);
        if (false !== ($data = $this->getData($response))) {
            return $data ?: [];
        } else {
            throw new SalesviewerApiException($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $data
     * @param array  $headers
     * @return GuzzleResponse Response from the service.
     */
    public function send ($method, $url, $data = [], $headers = []) {

        try {

            $request = new GuzzleRequest(
                $method,
                $url,
                array_merge([
                    'X-SV-APIKEY' => $this->config['sv_api_key'],
                ], $headers)
            );
            $get_data = $method == 'get' ? $data : [];
            $post_data = $method == 'post' ? $data : [];
            $response = $this->client->send($request, [
                'query' => $get_data,
                'json' => $post_data,
            ]);

            return $response;

        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response;
            }
            return new GuzzleResponse($e->getCode() ?: 500, [], null, '1.1', $e->getMessage());

        } catch (GuzzleException $e) {

            return new GuzzleResponse($e->getCode() ?: 500, [], null, '1.1', $e->getMessage());

        } catch (\Exception $e) {

            return new GuzzleResponse(500, [], null, '1.1', $e->getMessage());

        }
    }

    /**
     * @param GuzzleResponse $response
     * @return bool|mixed
     */
    protected function getData (GuzzleResponse $response) {

        try {

            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody(), true);
            }

            return false;
        } catch (\Exception $e) {
            //invalid json data
            return false;
        }

    }

}