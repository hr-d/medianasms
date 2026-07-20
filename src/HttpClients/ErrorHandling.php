<?php


namespace HRD\MedianaSMS\HttpClients;

use HRD\MedianaSMS\Exceptions\MedianaSMSException;

class ErrorHandling
{
    /**
     * @param \Throwable $exception
     * @param array $context  ['url' => ..., 'method' => ...]
     *
     * @throws MedianaSMSException
     */
    public function fire(\Throwable $exception, array $context = []): void
    {
        $response = method_exists($exception, 'getResponse')
            ? $exception->getResponse()
            : null;

        // No response at all — network/connection error
        if (!$response) {
            $handlerError = method_exists($exception, 'getHandlerContext')
                ? ($exception->getHandlerContext()['error'] ?? null)
                : null;

            throw new MedianaSMSException(
                sprintf(
                    "MedianaSMS Connection Failed [%s %s]: %s",
                    $context['method'] ?? 'UNKNOWN',
                    $context['url'] ?? 'unknown',
                    $handlerError ?: $exception->getMessage()
                ),
                $exception->getCode() ?: 500,
                $exception,
                $context
            );
        }

        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();

        if ($body === '') {
            throw new MedianaSMSException(
                sprintf(
                    "MedianaSMS returned empty response [%s %s] — HTTP %d",
                    $context['method'] ?? 'UNKNOWN',
                    $context['url'] ?? 'unknown',
                    $statusCode
                ),
                $statusCode,
                $exception,
                $context
            );
        }

        $result = json_decode($body, true);

        if (
            isset($result['meta']['code']) &&
            $result['meta']['code'] !== 'OK'
        ) {
            $code = $result['meta']['code'];
            $message = $result['meta']['errorMessage'] ?? 'Unknown error';
            $errors = $result['meta']['errors'] ?? [];

            throw new MedianaSMSException(
                sprintf(
                    "MedianaSMS API Error [%s]: %s\n[%s %s]",
                    $code,
                    $message,
                    $context['method'] ?? 'UNKNOWN',
                    $context['url'] ?? 'unknown'
                ),
                $statusCode,
                $exception,
                $context,
                $errors
            );
        }

        if ($statusCode !== 200) {
            throw new MedianaSMSException(
                sprintf(
                    "MedianaSMS Unexpected HTTP %d [%s %s]\nBody: %s",
                    $statusCode,
                    $context['method'] ?? 'UNKNOWN',
                    $context['url'] ?? 'unknown',
                    substr($body, 0, 500)
                ),
                $statusCode,
                $exception,
                $context
            );
        }

        throw new MedianaSMSException(
            $exception->getMessage(),
            $statusCode,
            $exception,
            $context
        );
    }
}