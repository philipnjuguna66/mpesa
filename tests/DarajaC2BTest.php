<?php

namespace Rickodev\Mpesa\Tests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rickodev\Mpesa\Configs\C2BConfig;
use Rickodev\Mpesa\DarajaC2B;

class DarajaC2BTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws GuzzleException
     */
    public function test_c2b_v1_urls_can_be_registered()
    {
        $successfulRegistration = [
            "OriginatorCoversationID" => "36140-27770309-1",
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

        $responseBody =  $response->getBody()->getContents();

        $object = json_decode($responseBody);

        $this->assertEquals('36140-27770309-1',$object->OriginatorCoversationID);
        $this->assertEquals('0',$object->ResponseCode);
        $this->assertEquals('Success',$object->ResponseDescription);

    }

}
