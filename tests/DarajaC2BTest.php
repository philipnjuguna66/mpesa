<?php

namespace Rickodev\Mpesa\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rickodev\Mpesa\Configs\C2BConfig;
use Rickodev\Mpesa\DarajaC2B;
use Rickodev\Mpesa\Http\BaseDarajaResponse;
use Rickodev\Mpesa\Results\C2B\C2BV1Result;
use Rickodev\Mpesa\Results\C2B\C2BV2Result;


class DarajaC2BTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws GuzzleException
     */
    public function test_c2b_v1_urls_can_be_registered()
    {
        $successfulRegistration = [
            "OriginatorConversationID" => "36140-27770309-1",
            "ResponseCode" => "0",
            "ResponseDescription" => "Success"
        ];
        $successfulToken = [
            "access_token" => "sdaswfdsfdsewrewre",
        ];

        /**
         *
         * 1.mock successful access token retrieval response
         * 2.mock successful url registration response
         *
         */

        $mock = new MockHandler([
            new Response(200,body:json_encode($successfulToken)),
            new Response(200,body:json_encode($successfulRegistration)),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $configuration = new C2BConfig(
            key: 'unhkI9kwSDj1SOGq1C88UTARBfVnS963',
            secret: 'PPYxwx1hGgJtFq1U',
            shortCode: '600997',
            confirmationUrl: 'https://mydomain.com/confirmation',
            validationUrl: 'https://mydomain.com/validation',
            lnmPassKey: 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',
            stkCallBackUrl: 'https://mydomain.com/stk-callback-url',
            version: 1
        );

        $darajaService = new DarajaC2B(configuration:$configuration,overrides: ['handler' => $handlerStack]);

        $response  = $darajaService->registerUrls();

        $this->assertInstanceOf(BaseDarajaResponse::class,$response);
    }


    /**
     * @throws GuzzleException
     */
    public function test_cb2_v1_successful_validation_or_confirmation_results()
    {
        $validationResponseBody = [
            "TransactionType" => "Pay Bill",
            "TransID" => "RKTQDM7W6S",
            "TransTime" => "20191122063845",
            "TransAmount" => "10",
            "BusinessShortCode" => "600638",
            "BillRefNumber" => "254708374149",
            "InvoiceNumber" => "",
            "OrgAccountBalance" => "49197.00",
            "ThirdPartyTransID" => "",
            "MSISDN" => "254708374149",
            "FirstName" => "John",
            "MiddleName" => "",
            "LastName" => "Doe"
        ];

        $callbackResponseMock = new MockHandler([
            new Response(200,body:json_encode($validationResponseBody)),
            new Response(200,body:json_encode($validationResponseBody)),
        ]);

        $webHookInvoker = new Client(['handler' => $callbackResponseMock]);

        $ValidationResponse =     $webHookInvoker->post('https://mydomain.com/validation',[
            'json'=>$validationResponseBody
        ]);

        $ConfirmationResponse =     $webHookInvoker->post('https://mydomain.com/confimation',[
            'json'=>$validationResponseBody
        ]);

        $ValidationResponseBody =  $ValidationResponse->getBody()->getContents();
        $ConfirmationResponseBody =  $ConfirmationResponse->getBody()->getContents();

        $ValidationResponseBodyObject = json_decode($ValidationResponseBody);

        $ConfirmationResponseBodyObject = json_decode($ConfirmationResponseBody);

        $this->assertInstanceOf(C2BV1Result::class,C2BV1Result::fromResponseObject($ValidationResponseBodyObject));
        $this->assertInstanceOf(C2BV1Result::class,C2BV1Result::fromResponseObject($ConfirmationResponseBodyObject));

    }


    /**
     * @throws GuzzleException
     */
    public function test_cb2_v2_successful_validation_or_confirmation_results()
    {
        $validationResponseBody = [
            "TransactionType" => "Pay Bill",
            "TransID" => "RKTQDM7W6S",
            "TransTime" => "20191122063845",
            "TransAmount" => "10",
            "BusinessShortCode" => "600638",
            "BillRefNumber" => "254708374149",
            "InvoiceNumber" => "",
            "OrgAccountBalance" => "49197.00",
            "ThirdPartyTransID" => "",
            "MSISDN" => "2547*****149",
            "FirstName" => "John",
        ];

        $callbackResponseMock = new MockHandler([
            new Response(200,body:json_encode($validationResponseBody)),
            new Response(200,body:json_encode($validationResponseBody)),
        ]);

        $webHookInvoker = new Client(['handler' => $callbackResponseMock]);

        $ValidationResponse =     $webHookInvoker->post('https://mydomain.com/validation',[
            'json'=>$validationResponseBody
        ]);

        $ConfirmationResponse =     $webHookInvoker->post('https://mydomain.com/confimation',[
            'json'=>$validationResponseBody
        ]);

        $ValidationResponseBody =  $ValidationResponse->getBody()->getContents();
        $ConfirmationResponseBody =  $ConfirmationResponse->getBody()->getContents();

        $ValidationResponseBodyObject = json_decode($ValidationResponseBody);

        $ConfirmationResponseBodyObject = json_decode($ConfirmationResponseBody);

        $this->assertInstanceOf(C2BV2Result::class,C2BV2Result::fromResponseObject($ValidationResponseBodyObject));
        $this->assertInstanceOf(C2BV2Result::class,C2BV2Result::fromResponseObject($ConfirmationResponseBodyObject));

    }

}
