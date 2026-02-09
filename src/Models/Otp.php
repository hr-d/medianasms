<?php

namespace HRD\MedianaSMS\Models;

/**
 * Class Otp
 * @package HRD\MedianaSMS\Models
 */
class Otp
{

    /**
     * @var string
     */
    private $verificationCode;

    /**
     * @var string
     */
    private $patternCode;

    /**
     * @var string
     */
    private $destinationAddress;

    /**
     * @var string
     */
    private $UDH = null;

    /**
     * @var string
     */
    private $messageId;

    /**
     * @var array
     */
    private $response;

    /**
     * set verificationCode
     * @param string $verificationCode
     *
     * @return Otp
     */
    public function setVerificationCode(string $verificationCode)
    {
        $this->verificationCode = $verificationCode;
        return $this;
    }

    /**
     * set patternCode
     * @param string $patternCode
     *
     * @return Otp
     */
    public function setPatternCode(string $patternCode)
    {
        $this->patternCode = $patternCode;
        return $this;
    }

    /**
     * set destinationAddress
     * @param string $destinationAddress
     *
     * @return Otp
     */
    public function setDestinationAddress(string $destinationAddress)
    {
        $this->destinationAddress = $destinationAddress;
        return $this;
    }

    /**
     * set tag
     * @param string $tag
     *
     * @return Otp
     */
    public function setTag(string $tag)
    {
        $this->UDH = $tag;
        return $this;
    }

    /**
     * get messageId
     *
     * @return string
     */
    public function setMessageId(string $messageId)
    {
        return $this->messageId = $messageId;
    }

    /**
     * get messageId
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * get tage
     *
     * @return string
     */
    public function getTag()
    {
        return $this->UDH;
    }

    /**
     * toArray Attributes
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'recipient' => $this->destinationAddress,
            'patternCode' => $this->patternCode,
            'otpCode' => $this->verificationCode,
            'clientRef' => $this->getTag(),
        ];
    }

    /**
     * set provider response
     *
     */
    public function setResponse( array $response = [])
    {
        $this->response = $response;
    }

    /**
     * set provider response
     *
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}
