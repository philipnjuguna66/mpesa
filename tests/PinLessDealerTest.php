<?php

namespace Rickodev\Mpesa\Tests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rickodev\Mpesa\Configs\PinLessDealerConfig;
use Rickodev\Mpesa\PinLessDealer;
use PHPUnit\Framework\TestCase;
use Rickodev\Mpesa\Results\PinLessDealer\PinLessDealerSuccessFulResponse;

class PinLessDealerTest extends TestCase
{

    /**
     */
    public function testSuccessfulC2sRecharge()
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

       $this->assertInstanceOf(PinLessDealerSuccessFulResponse::class,$response->data);

    }

    public function testItHandlesErrors()
    {
        $invalid = [
            "access_token" => "sdaswfdsfdsewrewre",
            "expires_in" => "3599"

        ];

        $errorBody = [
            "requestId" => "11728-2929992-1",
            "errorCode" => "401.002.01",
            "errorMessage" => "Error Occurred - Invalid Access Token - BJGFGOXv5aZnw90KkA4TDtu4Xdyf"
        ];

        $mock = new MockHandler([
            new Response(200,body:json_encode($invalid)),
            new Response(401,body:json_encode($errorBody)),
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

        $this->assertEquals("Error Occurred - Invalid Access Token - BJGFGOXv5aZnw90KkA4TDtu4Xdyf",$response->message);
    }
}
