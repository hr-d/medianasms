<?php

namespace HRD\MedianaSMS;

use HRD\MedianaSMS\HttpClients\Request;
use HRD\MedianaSMS\Models\Message;
use HRD\MedianaSMS\Models\Otp;
use HRD\MedianaSMS\Models\PatternSms;

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
            $response = $this->request->make('sms/v1/send/sms', 'POST', $data);
            if (!empty($response['data']['requestId'])) {
                $message->setMessageId($response['data']['requestId']);
                $message->setResponse((array)$response);
                return $message;
            } else {
                throw new \Exception("please connect to MedianaSMS " . PHP_EOL . serialize($response), 500);
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function sendOtp(string $verificationCode, string $patternCode, string $destinationAddress, string $tag = null)
    {
        $otp = new Otp();
        $otp
            ->setVerificationCode($verificationCode)
            ->setPatternCode($patternCode)
            ->setDestinationAddress($destinationAddress)
            ->setTag($tag);
        $data = $otp->toArray();
        try {
            $response = $this->request->make('sms/v1/send/otp', 'POST', $data);
            if (!empty($response['data']['requestId'])) {
                $otp->setMessageId($response['data']['requestId']);
                $otp->setResponse((array)$response);
                return $otp;
            } else {
                throw new \Exception("please connect to MedianaSMS " . PHP_EOL . serialize($response), 500);
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function sendPatternSms(string $sourceAddress, string $patternCode, array $parameters, string $destinationAddress, string $tag = null)
    {
        $patternSms = new PatternSms();
        $patternSms
            ->setParameters($parameters)
            ->setPatternCode($patternCode)
            ->setDestinationAddress($destinationAddress)
            ->setSourceAddress($sourceAddress)
            ->setTag($tag);
        $data = $patternSms->toArray();
        try {
            $response = $this->request->make('sms/v1/send/pattern', 'POST', $data);
            if (!empty($response['data']['requestId'])) {
                $patternSms->setMessageId($response['data']['requestId']);
                $patternSms->setResponse((array)$response);
                return $patternSms;
            } else {
                throw new \Exception("please connect to MedianaSMS " . PHP_EOL . serialize($response), 500);
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
