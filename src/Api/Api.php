<?php

namespace Bixie\Datacollectief\Api;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Uri;

class Api {

    use WebsiteleadsTrait, ToolsTrait, CompanyTrait, ContactTrait;

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
//            $get_data = $method == 'get' ? $data : [];
//            $post_data = $method == 'post' ? $data : [];
            //post data goes in URI???
            $post_data = [];
            $get_data = $data;
            $response = $this->client->send($request, [
                'query' => array_merge([
                        'applicationName' => $this->config['application_name'],
                        'user' => $this->config['user'],
                        'password' => $this->config['password'],
                    ], $get_data),
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
     * @param       $url
     * @param array $datas
     * @param array $headers
     * @return GuzzleResponse[]
     */
    protected function getPool ($url, $datas = [], $headers = []) {

        $uri = new Uri($url);
        $requests = array_map(function ($data) use ($uri, $headers) {
            $query = http_build_query(array_merge([
                'applicationName' => $this->config['application_name'],
                'user' => $this->config['user'],
                'password' => $this->config['password'],
            ], $data), null, '&', PHP_QUERY_RFC3986);

            return new GuzzleRequest(
                'GET',
                $uri->withQuery($query),
                array_merge([], $headers)
            );
        }, $datas);

        $responses = Pool::batch($this->client, $requests, ['concurrency' => 20,]);

        return $responses;
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

            return '';
        } catch (\Exception $e) {
            return 'Error in response body';
        }

    }

}