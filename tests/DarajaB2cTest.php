<?php

namespace Rickodev\Mpesa\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Rickodev\Mpesa\Configs\B2cConfig;
use Rickodev\Mpesa\DarajaB2C;
use Rickodev\Mpesa\Http\BaseDarajaResponse;
use Rickodev\Mpesa\Results\B2C\B2CReversalResultResolver;
use Rickodev\Mpesa\Results\B2C\B2CSuccessfulResponseResult;
use Rickodev\Mpesa\Results\B2C\B2cSuccessfulReversalResult;
use Rickodev\Mpesa\Results\BaseErrorResponseResult;

class DarajaB2cTest extends TestCase
{

    public function test_successful_b2c_request_response()
    {

        $successfulToken = [
            "access_token" => "sdaswfdsfdsewrewre",
            "expires_in" => "3599"

        ];

        $successfulB2cResponse = [
            "ConversationID" => "AG_20191219_00005797af5d7d75f652",
            "OriginatorConversationID" => "16740-34861180-1",
            "ResponseCode" => "0",
            "ResponseDescription" => "Accept the service request successfully."
        ];

        //mock expected responses

        $mock = new MockHandler([
            new Response(200, body: json_encode($successfulToken)),
            new Response(200, body: json_encode($successfulB2cResponse)),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $configuration = new B2cConfig(
            key: 'unhkI9kwSDj1SOGq1C88UTARBfVnS963',
            secret: 'PPYxwx1hGgJtFq1U',
            shortCode: '600997',
            resultsUrl: 'https://mydomain.com/results',
            timeOutUrl: 'https://mydomain.com/timeout',
            initiatorName: 'testapi',
            initiatorPassword: 'Safaricom999!*!'
        );

        $b2c = new DarajaB2C(configuration: $configuration, overrides: ['handler' => $handlerStack]);

        $response = $b2c->send(phone: '254708374149', amount: 10);

        $this->assertInstanceOf(B2CSuccessfulResponseResult::class, $response->data);
    }


    public function test_erroneous_b2c_request_response()
    {
        $invalidToken = [
            "access_token" => "BJGFGOXv5aZnw90KkA4TDtu4Xdyf",
            "expires_in" => "3599"
        ];

        $errorBody = [
            "requestId" => "11728-2929992-1",
            "errorCode" => "401.002.01",
            "errorMessage" => "Error Occurred - Invalid Access Token - BJGFGOXv5aZnw90KkA4TDtu4Xdyf"
        ];

        $mock = new MockHandler([
            new Response(200, body: json_encode($invalidToken)),
            new Response(401, body: json_encode($errorBody)),
        ]);


        $handlerStack = HandlerStack::create($mock);

        $configuration = new B2cConfig(
            key: 'unhkI9kwSDj1SOGq1C88UTARBfVnS963',
            secret: 'PPYxwx1hGgJtFq1U',
            shortCode: '600997',
            resultsUrl: 'https://mydomain.com/results',
            timeOutUrl: 'https://mydomain.com/timeout',
            initiatorName: 'testapi',
            initiatorPassword: 'Safaricom999!*!'
        );

        $b2c = new DarajaB2C(configuration: $configuration, overrides: ['handler' => $handlerStack]);

        $response = $b2c->send(phone: '254708374149', amount: 10);

        $this->assertInstanceOf(BaseErrorResponseResult::class, $response->data);

        $this->assertEquals("Error Occurred - Invalid Access Token - BJGFGOXv5aZnw90KkA4TDtu4Xdyf", $response->message);
    }


    /**
     * @throws GuzzleException
     */
    public function test_b2_reversal()
    {

        $successfulToken = [
            "access_token" => "sdaswfdsfdsewrewre",
            "expires_in" => "3599"

        ];

        $successfulB2cResponse = [
            "ConversationID" => "AG_20191219_00005797af5d7d75f652",
            "OriginatorConversationID" => "16740-34861180-1",
            "ResponseCode" => "0",
            "ResponseDescription" => "Accept the service request successfully."
        ];

        $mock = new MockHandler([
            new Response(200, body: json_encode($successfulToken)),
            new Response(200, body: json_encode($successfulB2cResponse)),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $configuration = new B2cConfig(
            key: 'unhkI9kwSDj1SOGq1C88UTARBfVnS963',
            secret: 'PPYxwx1hGgJtFq1U',
            shortCode: '600997',
            resultsUrl: 'https://mydomain.com/results',
            timeOutUrl: 'https://mydomain.com/timeout',
            initiatorName: 'testapi',
            initiatorPassword: 'Safaricom999!*!'
        );

        $b2c = new DarajaB2C(configuration: $configuration, overrides: ['handler' => $handlerStack]);

        $response = $b2c->reverse(transactionID: 'MJ561H6X5O', amount: 10);

        $this->assertInstanceOf(B2CSuccessfulResponseResult::class, $response->data);


        $responseBody = [
            "Result" => [
                "ResultType" => 0,
                "ResultCode" => 21,
                "ResultDesc" => "The service request is processed successfully",
                "OriginatorConversationID" => "8521-4298025-1",
                "ConversationID" => "AG_20181005_00004d7ee675c0c7ee0b",
                "TransactionID" => "MJ561H6X5O",
                "ResultParameters" => [
                    "ResultParameter" => [
                        [
                            "Key" => "DebitAccountBalance",
                            "Value" => "Utility Account|KES|51661.00|51661.00|0.00|0.00"
                        ],
                        [
                            "Key" => "Amount",
                            "Value" => 100
                        ],
                        [
                            "Key" => "TransCompletedTime",
                            "Value" => 20181005153225
                        ],
                        [
                            "Key" => "OriginalTransactionID",
                            "Value" => "MJ551H6X5D"
                        ],
                        [
                            "Key" => "Charge",
                            "Value" => 0
                        ],
                        [
                            "Key" => "CreditPartyPublicName",
                            "Value" => "254708374149 - John Doe"
                        ],
                        [
                            "Key" => "DebitPartyPublicName",
                            "Value" => "601315 - Safaricom1338"
                        ]
                    ]
                ],
                "ReferenceData" => [
                    "ReferenceItem" => [
                        "Key" => "QueueTimeoutURL",
                        "Value" => "https://internalsandbox.safaricom.co.ke/mpesa/reversalresults/v1/submit"
                    ]
                ]
            ]
        ];

        $callbackResponseMock = new MockHandler([
            new Response(200,body:json_encode($responseBody)),
        ]);


        $webHookInvoker = new Client(['handler' => $callbackResponseMock]);

        $callBackInvokeRequest = $webHookInvoker->post('https://mydomain.com/results', [
            'json' => $responseBody
        ]);

        $responseBodyObject = json_decode($callBackInvokeRequest->getBody()->getContents());


        $this->assertInstanceOf(B2cSuccessfulReversalResult::class,B2CReversalResultResolver::fromResponseObject($responseBodyObject));


    }

}
