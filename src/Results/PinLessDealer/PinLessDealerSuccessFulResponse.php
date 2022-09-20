<?php
namespace Rickodev\Mpesa\Results\PinLessDealer;


use Rickodev\Mpesa\Results\BaseResult;

class PinLessDealerSuccessFulResponse implements BaseResult
{

    public function __construct(
        public string $responseId,
        public string $responseDesc,
        public string $responseStatus,
        public string $transId,
    )
    {
    }

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return  new self(
            responseId: $responseBody->responseId,
            responseDesc: $responseBody->responseDesc,
            responseStatus: $responseBody->responseStatus,
            transId: $responseBody->transId,
        );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return  new self(
            responseId: $responseBody['responseId'],
            responseDesc: $responseBody['responseDesc'],
            responseStatus: $responseBody['responseStatus'],
            transId: $responseBody['transId'],
        );
    }

    public function toArray(): array
    {
       return [
           'responseId'=>$this->responseId,
           'responseStatus'=>$this->responseStatus,
           'responseDesc'=>$this->responseDesc,
           'transId'=>$this->transId,
       ];
    }
}
