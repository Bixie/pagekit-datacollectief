<?php


namespace Bixie\Datacollectief\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class Api {

    use WebsiteleadsTrait;

    protected $config = [];

    protected $debug = false;

    /**
     * Method used to communicate with service. Defaults to POST request.
     * @var Client
     */
    protected $client;

    /**
     * Api constructor.
     * @param array $config
     * @param bool  $debug
     */
    public function __construct (array $config, $debug) {
        $this->config = $config;
        $this->debug = $debug;

        $this->client = new Client(['base_uri' => $this->config['api_url']]);
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
            $response = $this->client->send($request, ['query' => array_merge([
                'ApplicationKey' => $this->config['application_key'],
                'LicenseName' => $this->config['license_name'],
                'Password' => $this->config['password'],
            ], $data)]);

            return $response;

        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response;
            }
            return new GuzzleResponse($e->getCode(), [], null, ['reason_phrase' => $e->getMessage()]);

        } catch (GuzzleException $e) {

            return new GuzzleResponse($e->getCode(), [], null, ['reason_phrase' => $e->getMessage()]);
        } catch (\Exception $e) {

            return new GuzzleResponse($e->getCode(), [], null, ['reason_phrase' => $e->getMessage()]);
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
            return false;
        }

    }

}