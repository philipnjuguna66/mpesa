<?php


namespace Rickodev\Mpesa;


use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Rickodev\Mpesa\Configs\C2BConfig;
use Rickodev\Mpesa\Http\BaseDarajaResponse;
use Rickodev\Mpesa\Http\IDarajaResponse;
use Rickodev\Mpesa\Results\C2B\C2BSimulateResult;
use Rickodev\Mpesa\Results\C2B\UrlRegistrationResult;


class DarajaC2B extends DarajaService
{

    protected Configs\BaseConfig $config;

    public function __construct(private C2BConfig $configuration, array $overrides = [])
    {
        $this->config = $this->configuration;

        parent::__construct($overrides);

    }


    public function registerUrls(): BaseDarajaResponse
    {
        $version = $this->config->version;

        $requestData = [
            'ShortCode' => $this->config->shortCode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $this->config->confirmationUrl,
            'ValidationURL' => $this->config->validationUrl,
        ];

        $token = $this->getToken();

        try {
            $response = $this->getClient()->post("mpesa/c2b/v$version/registerurl", [
                'json' => $requestData,
                'headers' => [
                    "Authorization" => "Bearer $token",
                ]
            ]);

            $response = $response->getBody()->getContents();

            /** @var UrlRegistrationResult $result */

            $result = UrlRegistrationResult::fromResponseObject(json_decode($response));

            return BaseDarajaResponse::successful($result->ResponseDescription, $result);


        } catch (BadResponseException $e) {

            return BaseDarajaResponse::failed($e->getResponse()->getReasonPhrase(),data:null);

        } catch (Exception|GuzzleException $e) {

            return BaseDarajaResponse::failed($e->getMessage(),data:null);

        }
    }


    /**
     * @throws GuzzleException
     */
    public function simulate($amount = 1, $reference = "test", $command = "CustomerPayBillOnline"): IDarajaResponse
    {

        $version = $this->config->version;

        $phone = "254708374149";

        $token = $this->getToken();

        $requestData = array(
            "ShortCode" => $this->config->shortCode,
            "CommandID" => $command,
            "Amount" => round($amount),
            "Msisdn" => $phone,
            "BillRefNumber" => $reference,
        );

        try {
            $response = $this->getClient()->post("mpesa/c2b/v$version/simulate", [
                'json' => $requestData,
                'headers' => [
                    "Authorization" => "Bearer $token",
                ]
            ]);
            $response = $response->getBody()->getContents();

            /** @var C2BSimulateResult $result */

            $result = C2BSimulateResult::fromResponseObject(json_decode($response));

            return BaseDarajaResponse::successful($result->ResponseDescription, $result);


        } catch (ClientException $e) {


            return BaseDarajaResponse::failed($e->getResponse()->getReasonPhrase(),data: null);
        }
    }
}
