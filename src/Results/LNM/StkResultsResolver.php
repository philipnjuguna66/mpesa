<?php

namespace Rickodev\Mpesa\Results\LNM;

use Rickodev\Mpesa\Results\BaseResult;


class StkResultsResolver implements BaseResult
{

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        if ($responseBody->Body->stkCallback->ResultCode === 0) {

            return StkSuccessfulResult::fromResponseObject($responseBody);
        }
        return StkErrorResult::fromResponseObject($responseBody);
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        if($responseBody['Body']['stkCallback']['ResultCode'] === 0){

            return StkSuccessfulResult::fromResponseArray($responseBody);
        }

        return StkErrorResult::fromResponseArray($responseBody);
    }

    public function toArray(): array
    {
        return [];
    }
}
