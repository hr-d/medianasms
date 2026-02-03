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
            $response = $this->request->make('sms/v1/send/sms', 'POST', [$data]);
            if (!empty($response['data']['smsItems'][0]['smsItemId'])) {
                $message->setMessageId($response['data']['smsItems'][0]['smsItemId']);
                $message->setResponse((array)$response);
                return $message;
            } else {
                throw new \Exception("please connect to MedianaSMS " . PHP_EOL . serialize($response), 500);
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
