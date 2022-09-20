<?php

namespace Rickodev\Mpesa\Results\B2C;

use Rickodev\Mpesa\Results\BaseResult;

class B2CReversalResultResolver implements BaseResult
{

    public static function fromResponseObject(object $responseBody): BaseResult
    {

        if ((int)$responseBody->Result->ResultType === 0) {

            return  B2cSuccessfulReversalResult::fromResponseObject($responseBody);

        }

        return  B2cErrorResult::fromResponseObject($responseBody);
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        if ((int)$responseBody['Result']['ResultType'] === 0) {

        return  B2cSuccessfulReversalResult::fromResponseArray($responseBody);

    }

        return  B2cErrorResult::fromResponseArray($responseBody);
    }

    public function toArray(): array
    {
        return [];
    }
}
