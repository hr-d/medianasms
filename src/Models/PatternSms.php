<?php

namespace HRD\MedianaSMS\Models;

/**
 * Class PatternSms
 * @package HRD\MedianaSMS\Models
 */
class PatternSms
{

    /**
     * @var string
     */
    private $sourceAddress;

    /**
     * @var array
     */
    private $parameters;

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
     * set parameters
     * @param array $parameters
     *
     * @return PatternSms
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * set patternCode
     * @param string $patternCode
     *
     * @return PatternSms
     */
    public function setPatternCode(string $patternCode)
    {
        $this->patternCode = $patternCode;
        return $this;
    }

    /**
     * set sourceAddress
     * @param string $sourceAddress
     *
     * @return PatternSms
     */
    public function setSourceAddress(string $sourceAddress)
    {
        $this->sourceAddress = $sourceAddress;
        return $this;
    }

    /**
     * set destinationAddress
     * @param string $destinationAddress
     *
     * @return PatternSms
     */
    public function setDestinationAddress(string $destinationAddress)
    {
        $this->destinationAddress = [$destinationAddress];
        return $this;
    }

    /**
     * set tag
     * @param string $tag
     *
     * @return PatternSms
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
            'recipients' => $this->destinationAddress,
            'patternCode' => $this->patternCode,
            'parameters' => $this->parameters,
            'sendingNumber' => $this->sourceAddress,
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
