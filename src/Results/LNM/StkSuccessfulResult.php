<?php

namespace Rickodev\Mpesa\Results\LNM;

use Rickodev\Mpesa\Results\BaseResult;

class StkSuccessfulResult implements BaseResult
{

    /**
     * @param string $MerchantRequestID
     * @param string $CheckoutRequestID
     * @param string $ResultDesc
     * @param float $Amount
     * @param string $MpesaReceiptNumber
     * @param string $TransactionDate
     * @param string $PhoneNumber
     * @param int $ResultCode
     */
    public function __construct(
        public string $MerchantRequestID,
        public string $CheckoutRequestID,
        public string $ResultDesc,
        public float $Amount,
        public string $MpesaReceiptNumber,
        public string $TransactionDate,
        public string $PhoneNumber,
        public int $ResultCode,
    )
    {

    }

    public static function fromResponseObject(object $responseBody): BaseResult
    {
        $date = $responseBody->Body->stkCallback->CallbackMetadata->Item[2]->Name === 'balance' ? $responseBody->Body->stkCallback->CallbackMetadata->Item[3]->Value : $responseBody->Body->stkCallback->CallbackMetadata->Item[2]->Value;
        $phone = $responseBody->Body->stkCallback->CallbackMetadata->Item[2]->Name === 'balance' ? $responseBody->Body->stkCallback->CallbackMetadata->Item[4]->Value : $responseBody->Body->stkCallback->CallbackMetadata->Item[3]->Value;

        return  new self(
            MerchantRequestID: $responseBody->Body->stkCallback->MerchantRequestID,
            CheckoutRequestID: $responseBody->Body->stkCallback->CheckoutRequestID,
            ResultDesc: $responseBody->Body->stkCallback->ResultDesc,
            Amount: $responseBody->Body->stkCallback->CallbackMetadata->Item[0]->Value,
            MpesaReceiptNumber: $responseBody->Body->stkCallback->CallbackMetadata->Item[1]->Value,
            TransactionDate: $date,
            PhoneNumber: $phone,
            ResultCode: $responseBody->Body->stkCallback->ResultCode,
        );
    }

    public static function fromResponseArray(array $responseBody): BaseResult
    {
        $date =  $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Name'] == 'Balance' ? $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'] : $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Value'];

        $phone = $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Name'] == 'Balance' ? $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'] : $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'];

        return  new self(
            MerchantRequestID: $responseBody['Body']['stkCallback']['MerchantRequestID'],
            CheckoutRequestID: $responseBody['Body']['stkCallback']['CheckoutRequestID'],
            ResultDesc: $responseBody['Body']['stkCallback']['ResultDesc'],
            Amount: $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'],
            MpesaReceiptNumber: $responseBody['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'],
            TransactionDate: $date,
            PhoneNumber: $phone,
            ResultCode:$responseBody['Body']['stkCallback']['ResultCode']
        );
    }

    public function toArray(): array
    {
        return [];
    }
}
