<?php

namespace HRD\MedianaSMS\HttpClients;

class Request
{
    const MEDIANASMS_BASE_API_URL_ENV_NAME = "MEDIANASMS_BASE_API_URL";
    const MEDIANASMS_KEY_ENV_NAME = "MEDIANASMS_KEY";

    /**
     * @var GuzzleHttpClient
     */
    private $client;


    /**
     * Constructor.
     *
     * @param GuzzleHttpClient|null $client
     */
    public function __construct(GuzzleHttpClient $client = null)
    {
        $this->client = $client ?: new GuzzleHttpClient();
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
        $Authorization = getenv(self::MEDIANASMS_KEY_ENV_NAME);
        try {
            return $this->client->make($this->get_apiUrl($path), $method, $params, null, [
                'X-API-KEY' => $Authorization]);
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
