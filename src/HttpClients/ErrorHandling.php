<?php


namespace HRD\MedianaSMS\HttpClients;

use GuzzleHttp\Exception\RequestException;

class ErrorHandling
{

    /**
     * @param int $statusCode
     * @param array $result
     *
     * @throws \Exception
     */
    public function fire($exception)
    {
        $response = $exception->getResponse();
        if (!$response) {
            throw $exception;
        }

        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();

        if ($body === '') {
            throw $exception;
        }

        $result = json_decode($body, true);

        if (
            isset($result['meta']['code']) &&
            $result['meta']['code'] !== 'OK'
        ) {
            $code = $result['meta']['code'];
            $message = $result['meta']['errorMessage'] ?? 'Unknown error';
            $errors = $result['meta']['errors'] ?? [];

            throw new \Exception(
                sprintf(
                    "MedianaSMS Error [%s]: %s\nErrors: %s",
                    $code,
                    $message,
                    json_encode($errors, JSON_UNESCAPED_UNICODE)
                ),
                $statusCode
            );
        }

        if ($statusCode !== 200) {
            throw new \Exception(
                sprintf(
                    "Failed to connect to MedianaSMS. HTTP %d\n%s",
                    $statusCode,
                    $body
                ),
                $statusCode
            );
        }

        throw $exception;
    }
}