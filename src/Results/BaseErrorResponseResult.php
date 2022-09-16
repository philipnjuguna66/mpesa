<?php

namespace Rickodev\Mpesa\Results;

class BaseErrorResponseResult implements BaseResult
{

    public function __construct(public string $requestId,public string $errorCode,public string $errorMessage)
    {
    }

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return new self(
            requestId: $responseBody->requestId,
            errorCode: $responseBody->errorCode,
            errorMessage: $responseBody->errorMessage);
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return new self(requestId: $responseBody['requestid'],errorCode: $responseBody['errorCode'],errorMessage: $responseBody['errorMessage']);
    }

    public function toArray(): array
    {
        return  [
            'requestid' => $this->requestId,
            'errorCode' => $this->errorCode,
            'errorMessage' => $this->errorMessage,
        ];
    }
}
