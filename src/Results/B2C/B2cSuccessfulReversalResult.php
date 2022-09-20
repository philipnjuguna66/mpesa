<?php

namespace Rickodev\Mpesa\Results\B2C;

use Rickodev\Mpesa\Results\BaseResult;

class B2cSuccessfulReversalResult implements BaseResult
{

    public function __construct(
        public int $ResultType,
        public int $ResultCode,
        public string $ResultDesc,
        public string $OriginatorConversationId,
        public string $ConversationId,
        public string $TransactionId,
        public string $DebitAccountBalance,
        public int $Amount,
        public int $TransCompletedTime,
        public string $OriginalTransactionId,
        public int $Charge,
        public string $CreditPartyPublicName,
        public string $DebitPartyPublicName,
    )
    {
    }

    /**
     * @param object $responseBody
     * @return BaseResult
     */
    public static function fromResponseObject(object $responseBody): BaseResult
    {
        print_r($responseBody);

       return  new self(
           ResultType: $responseBody->Result->ResultType,
           ResultCode: $responseBody->Result->ResultCode,
           ResultDesc: $responseBody->Result->ResultDesc,
           OriginatorConversationId: $responseBody->Result->OriginatorConversationID,
           ConversationId: $responseBody->Result->ConversationID,
           TransactionId: $responseBody->Result->TransactionID,
           DebitAccountBalance: $responseBody->Result->ResultParameters->ResultParameter[0]->Value,
           Amount: $responseBody->Result->ResultParameters->ResultParameter[1]->Value,
           TransCompletedTime: $responseBody->Result->ResultParameters->ResultParameter[2]->Value,
           OriginalTransactionId: $responseBody->Result->ResultParameters->ResultParameter[3]->Value,
           Charge: $responseBody->Result->ResultParameters->ResultParameter[4]->Value,
           CreditPartyPublicName: $responseBody->Result->ResultParameters->ResultParameter[5]->Value,
           DebitPartyPublicName: $responseBody->Result->ResultParameters->ResultParameter[6]->Value,
       );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        return  new self(

            ResultType: $responseBody['Result']["ResultType"],
            ResultCode: $responseBody['Result']["ResultCode"],
            ResultDesc: $responseBody['Result']["ResultDesc"],
            OriginatorConversationId: $responseBody['Result']["OriginatorConversationID"],
            ConversationId: $responseBody['Result']["ConversationID"],
            TransactionId: $responseBody['Result']["TransactionId"],
            DebitAccountBalance: $responseBody['Result']['ResultParameter'][0]['']["DebitAccountBalance"],
            Amount: $responseBody["Amount"],
            TransCompletedTime: $responseBody["TransCompletedTime"],
            OriginalTransactionId: $responseBody["OriginalTransactionId"],
            Charge: $responseBody["Charge"],
            CreditPartyPublicName: $responseBody["CreditPartyPublicName"],
            DebitPartyPublicName: $responseBody["DebitPartyPublicName"],


        );
    }

    public function toArray(): array
    {
        return [
            "ResultType" => $this->ResultType,
           "ResultCode" => $this->ResultCode,
           "ResultDesc" => $this->ResultDesc,
           "OriginatorConversationId" => $this->OriginatorConversationId,
           "ConversationId" => $this->ConversationId,
           "TransactionId" => $this->TransactionId,
           "DebitAccountBalance" => $this->DebitAccountBalance,
           "Amount" => $this->Amount,
           "TransCompletedTime" => $this->TransCompletedTime,
           "OriginalTransactionId" => $this->OriginalTransactionId,
           "Charge" => $this->Charge,
           "CreditPartyPublicName" => $this->CreditPartyPublicName,
           "DebitPartyPublicName" => $this->DebitPartyPublicName,
        ];
    }
}
