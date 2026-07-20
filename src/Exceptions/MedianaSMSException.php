<?php

namespace HRD\MedianaSMS\Exceptions;

class MedianaSMSException extends \Exception
{
    protected $context;
    protected $responseErrors;

    public function __construct(
        string $message = "",
        int $code = 0,
        \Throwable $previous = null,
        array $context = [],
        ?array $responseErrors = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->responseErrors = $responseErrors;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getResponseErrors(): ?array
    {
        return $this->responseErrors;
    }
}
