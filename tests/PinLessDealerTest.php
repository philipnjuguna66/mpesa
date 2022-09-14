<?php

namespace Rickodev\Mpesa\Tests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rickodev\Mpesa\Configs\PinLessDealerConfig;
use Rickodev\Mpesa\PinLessDealer;
use PHPUnit\Framework\TestCase;

class PinLessDealerTest extends TestCase
{

    /**
     * @throws GuzzleException
     */
    public function testC2sRecharge()
    {
        $successfulToken = [
            "access_token" => "sdaswfdsfdsewrewre",
            "expires_in" => "3599"

        ];

        $successfulC2sResponse = [
            "responseId" => "26772-3250989-5",
            "responseDesc" => "R200682.1904.260014 confirmed. You have topped up 722000000 with Ksh. 10. New balance is Ksh. 1162",
            "responseStatus" => "200",
            "transId" => " R200682.1904.260014 "
        ];

        $mock = new MockHandler([
            new Response(200,body:json_encode($successfulToken)),
            new Response(200,body:json_encode($successfulC2sResponse)),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $configuration = new PinLessDealerConfig(
            key: 'unhkI9kwSDj1SOGq1C88UTARBfVnS963',
            secret: 'PPYxwx1hGgJtFq1U',
            servicePin: 9090,
            senderMsisdn:'748248717'
        );
        $c2s = new PinLessDealer(configuration: $configuration,overrides: ['handler'=> $handlerStack]);

        $response  = $c2s->c2sRecharge(phoneNumber: "748248523",amountInKsh:100);

        $responseBody =  $response->getBody()->getContents();

        $responseBodyObject = json_decode($responseBody);

        $this->assertEquals('26772-3250989-5',$responseBodyObject->responseId);

    }
}
