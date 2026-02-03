<?php

namespace HRD\MedianaSMS\Models;

/**
 * Class Room
 * @package HRD\Alocom\Models
 */
class Message
{

    /**
     * @var string
     */
    private $sourceAddress;

    /**
     * @var string
     */
    private $messageText = "";

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
     * set sourceAddress
     * @param string $sourceAddress
     *
     * @return Message
     */
    public function setSourceAddress(string $sourceAddress)
    {
        $this->sourceAddress = $sourceAddress;
        return $this;
    }

    /**
     * set messageText
     * @param string $messageText
     *
     * @return Message
     */
    public function setMessageText(string $messageText)
    {
        $this->messageText = $messageText;
        return $this;
    }

    /**
     * set destinationAddress
     * @param string $destinationAddress
     *
     * @return Message
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
     * @return Message
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
            'messageText' => $this->messageText,
            'sendingNumber' => $this->sourceAddress,
            'UDH' => $this->getTag(),
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
