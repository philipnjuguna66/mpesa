<?php

namespace Rickodev\Mpesa\Models;

class C2BV1Result
{

    public function __construct(
        public string  $TransactionType,
        public string  $TransID,
        public string  $TransTime,
        public string  $TransAmount,
        public string  $BusinessShortCode,
        public string  $BillRefNumber,
        public ?string $InvoiceNumber,
        public string  $OrgAccountBalance,
        public string  $ThirdPartyTransID,
        public string  $MSISDN,
        public string  $FirstName,
        public ?string $MiddleName,
        public string  $LastName,

    )
    {

    }

   public  static function fromResponse(object $responseBody):self {

        return  new self(
            TransactionType: $responseBody->TransactionType,
            TransID: $responseBody->TransID,
            TransTime: $responseBody->TransTime,
            TransAmount: $responseBody->TransAmount,
            BusinessShortCode: $responseBody->BusinessShortCode,
            BillRefNumber: $responseBody->BillRefNumber,
            InvoiceNumber: $responseBody->InvoiceNumber,
            OrgAccountBalance: $responseBody->OrgAccountBalance,
            ThirdPartyTransID: $responseBody->ThirdPartyTransID,
            MSISDN: $responseBody->MSISDN,
            FirstName: $responseBody->FirstName,
            MiddleName: $responseBody->MiddleName,
            LastName: $responseBody->LastName,
        );

    }

}
