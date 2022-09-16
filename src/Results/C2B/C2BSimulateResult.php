<?php

namespace Rickodev\Mpesa\Results\C2B;

use Rickodev\Mpesa\Results\BaseResult;

class C2BSimulateResult implements BaseResult
{

    public function __construct(public string $OriginatorCoversationID,public  string $ResponseCode,public string $ResponseDescription)
    {

    }


    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return  new self(
            OriginatorCoversationID: $responseBody->OriginatorCoversationID,
            ResponseCode: $responseBody->ResponseCode,
            ResponseDescription: $responseBody->ResponseDescription,
        );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return  new self(
            OriginatorCoversationID:$responseBody['OriginatorCoversationID'],
            ResponseCode: $responseBody['ResponseCode'],
            ResponseDescription:$responseBody['ResponseDescription'],
        );

    }

    public function toArray(): array
    {
        return [
            'ResponseCode'=> $this->ResponseCode,
            'OriginatorCoversationID'=> $this->OriginatorCoversationID,
            'ResponseDescription'=> $this->ResponseDescription,
        ];
    }
}
