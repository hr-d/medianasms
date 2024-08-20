<?php

namespace HRD\MedianaSMS\HttpClients;

class Request
{
    const MEDIANASMS_BASE_API_URL_ENV_NAME = "MEDIANASMS_BASE_API_URL";
    const MEDIANASMS_USERNAME_ENV_NAME = "MEDIANASMS_USERNAME";
    const MEDIANASMS_PASSWORD_ENV_NAME = "MEDIANASMS_PASSWORD";

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
        $Authorization = $this->get_auth();
        try {
            return $this->client->make($this->get_apiUrl($path), $method, $params, null, [
                'Authorization' => "Bearer " . $Authorization]);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * get_auth
     *
     * @return string
     * @throws \Exception
     */
    private function get_auth()
    {
        $username = getenv(self::MEDIANASMS_USERNAME_ENV_NAME);
        $password = getenv(self::MEDIANASMS_PASSWORD_ENV_NAME);
        try {
            $result = $this->client->make($this->get_apiUrl('connect/token'), "post", null, [
                "username" => $username,
                "password" => $password,
            ], ['Content-Type' => 'application/x-www-form-urlencoded']);
            return $result['access_token'];
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
