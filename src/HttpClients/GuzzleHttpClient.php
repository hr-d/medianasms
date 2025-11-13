<?php

namespace HRD\MedianaSMS\HttpClients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

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
        try {
            \Log::info('Mediana SMS - Request Started', [
                'url' => $url,
                'method' => $method,
                'params_count' => count($params ?? []),
                'timeout' => $this->timeout,
            ]);
            $response = $this->client->request($method, $url, [
                'json' => $params,
                'form_params' => $formParam,
                'headers' => $headers,
                'timeout' => $this->timeout,
                'connect_timeout' => 5,
                'http_errors' => false,
            ]);
            \Log::info('Mediana SMS - Response Received', [
                'status_code' => $response->getStatusCode(),
            ]);
            $result = $response->getBody();

            if ($this->isJson($result)) {
                $result = json_decode($result, true);
            }

            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {
                return $result;
            }
        } catch (ClientException $exception) {
            \Log::error('Mediana SMS - ClientException', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'url' => $url,
                'method' => $method,
            ]);
            $this->errorHandling->fire($exception);
        } catch (ConnectException $exception) {
            \Log::error('Mediana SMS - Connection Failed (SSL/Network Error)', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'url' => $url,
                'method' => $method,
                'handshake_exception' => $exception->getHandlerContext()['error'] ?? 'N/A',
            ]);
            $this->errorHandling->fire($exception);
        } catch (\Exception $exception) {
            $this->errorHandling->fire($exception);
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
