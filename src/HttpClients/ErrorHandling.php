<?php


namespace HRD\MedianaSMS\HttpClients;


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
        try {
            $response = $exception->getResponse();
            $result = json_decode($response->getBody()->getContents(), true);
            $statusCode = $response->getStatusCode();
            $meta = $result['meta'];
            if (!empty($meta['code']) and $meta['code'] != 'OK') {
                throw new \Exception("medianaSMS error message: " . $meta['code'] .' - ' . $meta['errorMessage'] . PHP_EOL . 'errors: ' . serialize($meta['errors']), $statusCode);
            } else if (empty($meta['code']) and $statusCode != 200) {
                throw new \Exception("i don't know! please connect to MedianaSMS" . $exception->getMessage() . PHP_EOL . serialize($response->getBody()->getContents()), $statusCode);
            }
            throw $exception;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
