<?php

namespace Bixie\Datacollectief\Api;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class Api {

    use WebsiteleadsTrait, ToolsTrait;

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
        $this->config = array_merge(array_fill_keys(['api_url', 'application_name', 'user', 'password',], ''), $config);
        $this->debug = $debug;

        $this->client = new Client(['base_uri' => $this->config['api_url'], 'verify' => !$this->debug]);
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
                array_merge([], $headers)
            );
            //only GET requests are accepted, so all data must go in the query
            $response = $this->client->send($request, ['query' => array_merge([
                'applicationName' => $this->config['application_name'],
                'user' => $this->config['user'],
                'password' => $this->config['password'],
            ], $data)]);

            if ($error = $this->getErrorMessage($response)) {
                return new GuzzleResponse($response->getStatusCode(), [], null, '1.1', $error);
            }

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

    /**
     * @param GuzzleResponse $response
     * @return string
     */
    protected function getErrorMessage (GuzzleResponse $response) {

        try {

            if ($response->getStatusCode() > 200) {
                $data = json_decode($response->getBody(), true);
                if ($data && isset($data['Message'])) {
                    return $data['Message'];
                } else {
                    return $response->getReasonPhrase();
                }
            }

            return false;
        } catch (\Exception $e) {
            return 'Error in response body';
        }

    }

}