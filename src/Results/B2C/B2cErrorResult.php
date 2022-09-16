<?php

namespace Rickodev\Mpesa\Results\B2C;

use Rickodev\Mpesa\Results\BaseResult;

class B2cErrorResult implements BaseResult
{

    public function __construct(
        public int $ResultCode,
        public string $ResultDesc,
        public string $OriginatorConversationID,
        public string $ConversationID,
        public string $TransactionID,

    )
    {


    }

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return new self(
            ResultCode: $responseBody->Result->ResultCode,
            ResultDesc: $responseBody->Result->ResultDesc,
            OriginatorConversationID: $responseBody->Result->OriginatorConversationID,
            ConversationID: $responseBody->Result->ConversationID,
            TransactionID: $responseBody->Result->TransactionID,
        );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return new self(
            ResultCode: $responseBody['Result']['ResultCode'],
            ResultDesc: $responseBody['Result']['ResultDesc'],
            OriginatorConversationID: $responseBody['Result']['OriginatorConversationID'],
            ConversationID: $responseBody['Result']['ConversationID'],
            TransactionID: $responseBody['Result']['TransactionID'],
        );
    }

    public function toArray(): array
    {
        return [
            'ResultCode'=> $this->ResultCode,
            'ResultDesc'=> $this->ResultDesc,
            'OriginatorConversationID'=> $this->OriginatorConversationID,
            'ConversationID'=> $this->ConversationID,
            'TransactionID'=> $this->TransactionID,
        ];
    }
}
