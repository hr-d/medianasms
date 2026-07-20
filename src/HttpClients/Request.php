<?php

namespace HRD\MedianaSMS\HttpClients;

class Request
{
    const MEDIANASMS_BASE_API_URL_ENV_NAME = "MEDIANASMS_BASE_API_URL";

    /**
     * @var GuzzleHttpClient
     */
    private $client;

    /**
     * @var string
     */
    private $authorization;


    /**
     * Constructor.
     *
     * @param GuzzleHttpClient|null $client
     */
    public function __construct(string $authorization, GuzzleHttpClient $client = null)
    {
        $this->client = $client ?: new GuzzleHttpClient();
        $this->authorization = $authorization;
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function make(string $path, string $method, array $params)
    {
        try {
            return $this->client->make($this->get_apiUrl($path), $method, $params, null, [
                'X-API-KEY' => $this->authorization]);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private function get_apiUrl(string $path)
    {
        return getenv(self::MEDIANASMS_BASE_API_URL_ENV_NAME) . $path;
    }
}
