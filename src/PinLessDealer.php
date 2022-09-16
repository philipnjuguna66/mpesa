<?php

namespace Rickodev\Mpesa;


use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Rickodev\Mpesa\Configs\PinLessDealerConfig;
use Rickodev\Mpesa\Http\BaseDarajaResponse;
use Rickodev\Mpesa\Results\BaseErrorResponseResult;
use Rickodev\Mpesa\Results\PinLessDealer\PinLessDealerSuccessFulResponse;

class  PinLessDealer extends DarajaService
{

    protected Configs\BaseConfig $config;

    public function __construct(private PinLessDealerConfig $configuration, array $overrides = [])
    {
        $this->config = $this->configuration;

        parent::__construct($overrides);

    }


    public function c2sRecharge(string $phoneNumber, int $amountInKsh): BaseDarajaResponse
    {
        $requestData = [
            'amount' => $amountInKsh * 100, //convert to cents
            'senderMsisdn' => $this->config->senderMsisdn, // 748248717
            'servicePin' => base64_encode($this->config->servicePin), // 9090
            'receiverMsisdn' => $phoneNumber,
        ];

        $token = $this->getToken();

        try {
            $response = $this->getClient()->post('v1/pretups/api/recharge', [
                'json' => $requestData,
                'headers' => [
                    "Authorization" => "Bearer $token",
                ]
            ]);
            $response = $response->getBody()->getContents();

            /** @var PinLessDealerSuccessFulResponse $result */

            $result = PinLessDealerSuccessFulResponse::fromResponseObject(json_decode($response));

            return BaseDarajaResponse::successful(message: $result->responseDesc, data: $result);

        } catch (BadResponseException $e) {

            $errorResponse = $e->getResponse()->getBody()->getContents();

            $errorResponse =  BaseErrorResponseResult::fromResponseObject(json_decode($errorResponse));

            return BaseDarajaResponse::failed($errorResponse->errorMessage,data: $errorResponse);

        } catch (Exception|GuzzleException $e) {

            return BaseDarajaResponse::failed($e->getMessage(), data: null);

        }
    }

    protected function getToken(): string
    {
        try {
            $response = $this->getClient()->post('oauth2/v1/generate?grant_type=client_credentials', [
                'auth' => [$this->config->key, $this->config->secret]
            ]);

            $contents = $response->getBody()->getContents();

            $responseObj = json_decode($contents);

            return $responseObj->access_token;

        } catch (GuzzleException $e) {

            return "";
        }
    }

    protected function getBaseUrl(): string
    {
        return $this->isLive()
            ? "https://prod.safaricom.co.ke/"
            : "https://sandbox.safaricom.co.ke/";
    }
}
