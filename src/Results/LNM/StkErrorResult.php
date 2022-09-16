<?php

namespace Rickodev\Mpesa\Results\LNM;

use Rickodev\Mpesa\Results\BaseResult;

class StkErrorResult implements BaseResult
{

    public function __construct(
        public string $MerchantRequestID,
        public string $CheckoutRequestID,
        public string $ResultDesc,
        public int $ResultCode,

    )
    {
    }

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return  new self(
            MerchantRequestID: $responseBody->Body->stkCallback->MerchantRequestID,
            CheckoutRequestID: $responseBody->Body->stkCallback->CheckoutRequestID,
            ResultDesc: $responseBody->Body->stkCallback->ResultDesc,
            ResultCode: $responseBody->Body->stkCallback->ResultCode,
        );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return  new self(
            MerchantRequestID: $responseBody['Body']['stkCallback']['MerchantRequestID'],
            CheckoutRequestID: $responseBody['Body']['stkCallback']['CheckoutRequestID'],
            ResultDesc: $responseBody['Body']['stkCallback']['ResultDesc'],
            ResultCode:$responseBody['Body']['stkCallback']['ResultCode']
        );
    }

    public function toArray(): array
    {
        return [];
    }
}
