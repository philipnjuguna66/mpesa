<?php

namespace Rickodev\Mpesa\Results\B2C;
use Rickodev\Mpesa\Results\BaseResult;

class B2CResultsResolver implements BaseResult
{

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        if ((int)$responseBody->Result->ResultCode === 0) {

            return B2CSuccessfulResult::fromResponseObject($responseBody);
        }

        return B2cErrorResult::fromResponseObject($responseBody);
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        if ($responseBody['Result']['ResultCode'] === 0) {

            return B2CSuccessfulResult::fromResponseArray($responseBody);
        }

        return B2cErrorResult::fromResponseArray($responseBody);
    }


    public function toArray(): array
    {
        return [];
    }
}
