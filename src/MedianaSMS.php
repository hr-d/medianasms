<?php

namespace HRD\MedianaSMS;

use HRD\MedianaSMS\HttpClients\Request;
use HRD\MedianaSMS\Models\Message;

class MedianaSMS
{
    private $request, $message;

    public function __construct(Request $request, Message $message)
    {
        $this->request = $request;
        $this->message = $message;
    }

    public function sendMessage(string $sourceAddress, string $messageText, string $destinationAddress, string $tag = null)
    {
        $message = $this->message
            ->setSourceAddress($sourceAddress)
            ->setMessageText($messageText)
            ->setDestinationAddress($destinationAddress)
            ->setTag($tag);
        $data = $message->toArray();
        try {
            $response = $this->request->make('api/message/send', 'POST', [$data]);
            if ($response['succeeded'] == true and (int)$response['data'][0] > 0) {
                $message->setMessageId($response['data'][0]);
                return $message;
            } else {
                throw new \Exception("please connect to MedianaSMS " . PHP_EOL . json_encode($response), 500);
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
