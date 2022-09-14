<?php


namespace Rickodev\Mpesa;


use GuzzleHttp\Exception\GuzzleException;
use Rickodev\Mpesa\Configs\C2BConfig;

class DarajaC2B extends DarajaService
{

    protected Configs\BaseConfig $config;

    public function __construct(private C2BConfig $configuration)
    {
        $this->config = $this->configuration;

        parent::__construct();

    }


    /**
     * @throws GuzzleException
     */
    public function registerUrls($response_type = "Completed"): \Psr\Http\Message\ResponseInterface
    {
        $version = $this->config->version;

        $requestData = [
            'ShortCode' => $this->config->shortCode,
            'ResponseType' => $response_type,
            'ConfirmationURL' => $this->config->confirmationUrl,
            'ValidationURL' => $this->config->validationUrl,
        ];

        $token = $this->getToken();

        return $this->getClient()->post("mpesa/c2b/v$version/registerurl", [
            'json' => $requestData,
            'headers' => [
                "Authorization" => "Bearer $token",
            ]
        ]);

    }


    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    public function simulate($amount = 1, $reference = "test", $command = "CustomerPayBillOnline"): \Psr\Http\Message\ResponseInterface
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

        return $this->getClient()->post("mpesa/c2b/v$version/simulate", [
            'json' => $requestData,
            'headers' => [
                "Authorization" => "Bearer $token",
            ]
        ]);
    }
}
