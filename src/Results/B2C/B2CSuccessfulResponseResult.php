<?php

namespace Rickodev\Mpesa\Results\B2C;
use Rickodev\Mpesa\Results\BaseResult;

class B2CSuccessfulResponseResult implements BaseResult
{

    public function __construct(
        public string $ConversationID,
        public string $OriginatorConversationID,
        public string $ResponseCode,
        public string $ResponseDescription,
    ){}

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return new self(
            ConversationID: $responseBody->ConversationID,
            OriginatorConversationID: $responseBody->OriginatorConversationID,
            ResponseCode: $responseBody->ResponseCode,
            ResponseDescription: $responseBody->ResponseDescription
        );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return new self(
            ConversationID: $responseBody['ConversationID'],
            OriginatorConversationID:$responseBody['OriginatorConversationID'],
            ResponseCode: $responseBody['ResponseCode'],
            ResponseDescription: $responseBody['ResponseDescription'],
        );
    }

    public function toArray(): array
    {
        return  [
            "ConversationID"=> $this->ConversationID,
            "OriginatorConversationID"=>$this->OriginatorConversationID,
            "ResponseCode"=> $this->ResponseCode,
            "ResponseDescription" => $this->ResponseDescription,
        ];
    }
}
