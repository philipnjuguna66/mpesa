<?php

namespace Rickodev\Mpesa\Results\B2C;

use Rickodev\Mpesa\Results\BaseResult;

class B2CSuccessfulResult implements BaseResult
{


    public function __construct(
        public string $OriginatorConversationID,
        public string $ConversationID,
        public string $TransactionID,
        public int $TransactionAmount,
        public string $TransactionReceipt,
        public string $B2CRecipientIsRegisteredCustomer,
        public float $B2CChargesPaidAccountAvailableFunds,
        public string $ReceiverPartyPublicName,
        public string $TransactionCompletedDateTime,
        public float $B2CUtilityAccountAvailableFunds,
        public float $B2CWorkingAccountAvailableFunds,
    )
    {

    }

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        return new self(
            OriginatorConversationID: $responseBody->Result->OriginatorConversationID,
            ConversationID: $responseBody->Result->ConversationID,
            TransactionID: $responseBody->Result->TransactionID,
            TransactionAmount: $responseBody->Result->ResultParameters[0]->ResultParameter[0]->Value,
            TransactionReceipt: $responseBody->Result->ResultParameters[0]->ResultParameter[1]->Value,
            B2CRecipientIsRegisteredCustomer: $responseBody->Result->ResultParameters[0]->ResultParameter[2]->Value,
            B2CChargesPaidAccountAvailableFunds: $responseBody->Result->ResultParameters[0]->ResultParameter[3]->Value,
            ReceiverPartyPublicName: $responseBody->Result->ResultParameters[0]->ResultParameter[4]->Value,
            TransactionCompletedDateTime: $responseBody->Result->ResultParameters[0]->ResultParameter[5]->Value,
            B2CUtilityAccountAvailableFunds: $responseBody->Result->ResultParameters[0]->ResultParameter[6]->Value,
            B2CWorkingAccountAvailableFunds: $responseBody->Result->ResultParameters[0]->ResultParameter[7]->Value,
        );

    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return new self(
            OriginatorConversationID: $responseBody['Result']['OriginatorConversationID'],
            ConversationID: $responseBody['Result']['ConversationID'],
            TransactionID: $responseBody['Result']['TransactionID'],
            TransactionAmount: $responseBody['Result']['ResultParameters']['ResultParameter'][0]['Value'],
            TransactionReceipt: $responseBody['Result']['ResultParameters']['ResultParameter'][1]['Value'],
            B2CRecipientIsRegisteredCustomer: $responseBody['Result']['ResultParameters']['ResultParameter'][2]['Value'],
            B2CChargesPaidAccountAvailableFunds: $responseBody['Result']['ResultParameters']['ResultParameter'][3]['Value'],
            ReceiverPartyPublicName: $responseBody['Result']['ResultParameters']['ResultParameter'][4]['Value'],
            TransactionCompletedDateTime: $responseBody['Result']['ResultParameters']['ResultParameter'][5]['Value'],
            B2CUtilityAccountAvailableFunds: $responseBody['Result']['ResultParameters']['ResultParameter'][6]['Value'],
            B2CWorkingAccountAvailableFunds: $responseBody['Result']['ResultParameters']['ResultParameter'][7]['Value'],
        );
    }

    public function toArray(): array
    {
        return [
            'OriginatorConversationID'=> $this->OriginatorConversationID,
            'ConversationID' => $this->ConversationID,
            'TransactionID'=> $this->TransactionID,
            'TransactionAmount'=> $this->TransactionAmount,
            'TransactionReceipt'=> $this->TransactionReceipt,
            'B2CRecipientIsRegisteredCustomer'=> $this->B2CRecipientIsRegisteredCustomer,
            'B2CChargesPaidAccountAvailableFunds'=> $this->B2CChargesPaidAccountAvailableFunds,
            'ReceiverPartyPublicName'=> $this->ReceiverPartyPublicName,
            'TransactionCompletedDateTime'=> $this->TransactionCompletedDateTime,
            'B2CUtilityAccountAvailableFunds'=> $this->B2CUtilityAccountAvailableFunds,
            'B2CWorkingAccountAvailableFunds'=> $this->B2CWorkingAccountAvailableFunds,
        ];
    }
}
