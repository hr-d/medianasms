<?php

namespace HRD\MedianaSMS\HttpClients;

use GuzzleHttp\Client;

/**
 * Class GuzzleHttpClient.
 */
class GuzzleHttpClient
{
    /**
     * HTTP client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Timeout of the request in seconds.
     *
     * @var int
     */
    protected $timeout = 10;

    /**
     * @var ErrorHandling
     */
    protected $errorHandling;

    /**
     * GuzzleHttpClient constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
        $this->errorHandling = new ErrorHandling();
    }

    /**
     * make request
     *
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return mixed
     * @throws \Exception
     */
    public function make(string $url, string $method, array $params = null, array $formParam = null, array $headers = [])
    {
        $context = ['url' => $url, 'method' => $method];

        try {
            $response = $this->client->request($method, $url, [
                'json' => $params,
                'form_params' => $formParam,
                'headers' => $headers,
                'timeout' => $this->timeout,
                'connect_timeout' => 5,
                'http_errors' => true,
            ]);

            $result = $response->getBody();

            if ($this->isJson($result)) {
                $result = json_decode($result, true);
            }

            if ($response->getStatusCode() == 200) {
                return $result;
            }
        } catch (\Throwable $exception) {
            $this->errorHandling->fire($exception, $context);
        }
    }

    /**
     * Sets HTTP client.
     *
     * @param Client $client
     *
     * @return GuzzleHttpClient
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Gets HTTP client for internal class use.
     *
     * @return Client
     */
    private function getClient()
    {
        return $this->client;
    }

    /**
     * @param int $timeout
     * @return int
     */
    public function setTimeout(int $timeout)
    {
        return $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param $string
     * @return bool
     */
    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
