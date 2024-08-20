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
        $response = $exception->getResponse();
        $result = json_decode($response->getBody()->getContents(), true);
        $statusCode = $response->getStatusCode();
        dd($result, $statusCode, $response->getBody()->getContents());
        if (empty($result['succeeded'])) {
            throw new \Exception("i don't know! please connect to MedianaSMS" . $exception->getMessage() . PHP_EOL . serialize($response->getBody()->getContents()), $statusCode);
        }
        echo json_encode($result) . PHP_EOL;
        throw new \Exception($response->getBody()->getContents(), $statusCode);
    }
}
