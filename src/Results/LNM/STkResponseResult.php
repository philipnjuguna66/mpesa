<?php

namespace Rickodev\Mpesa\Results\LNM;

use Rickodev\Mpesa\Results\BaseResult;

class STkResponseResult implements BaseResult
{

    public function __construct(
        public string $MerchantRequestID,
        public string $CheckoutRequestID,
        public string $ResponseCode,
        public string $ResponseDescription,
        public  string $CustomerMessage,
    )
    {

    }

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return new self(
            MerchantRequestID: $responseBody->MerchantRequestID,
            CheckoutRequestID:$responseBody->CheckoutRequestID,
            ResponseCode: $responseBody->ResponseCode,
            ResponseDescription: $responseBody->ResponseDescription,
            CustomerMessage: $responseBody->CustomerMessage,
        );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return new self(
            MerchantRequestID: $responseBody['MerchantRequestID'],
            CheckoutRequestID:$responseBody['CheckoutRequestID'],
            ResponseCode: $responseBody['ResponseCode'],
            ResponseDescription: $responseBody['ResponseDescription'],
            CustomerMessage: $responseBody['CustomerMessage'],
        );
    }

    public function toArray(): array
    {
        return  [
            "MerchantRequestID"=> $this->MerchantRequestID,
            "CheckoutRequestID"=>$this->CheckoutRequestID,
            "ResponseCode"=> $this->ResponseCode,
            "ResponseDescription" => $this->ResponseDescription,
            "CustomerMessage" => $this->CustomerMessage,
        ];
    }
}
