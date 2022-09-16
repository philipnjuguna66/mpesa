<?php


namespace Rickodev\Mpesa;



use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Rickodev\Mpesa\Configs\B2cConfig;
use Rickodev\Mpesa\Http\BaseDarajaResponse;
use Rickodev\Mpesa\Results\B2C\B2CSuccessfulResponseResult;
use Rickodev\Mpesa\Results\BaseErrorResponseResult;


class DarajaB2C extends DarajaService
{
    protected Configs\BaseConfig $config;

    public function __construct(private B2cConfig $configuration, array $overrides = [])
    {
        $this->config = $this->configuration;

        parent::__construct($overrides);
    }


    public function send(string $phone, int $amount = 1, string $remarks = "Here are my remarks", string $occasion = "Payment", string $command = "BusinessPayment"):BaseDarajaResponse
    {
        $token = $this->getToken();

        $encryptedPassword = $this->isLive() ? $this->getLiveEncryptedPasswd($this->config->initiatorPassword) : $this->getSandBoxEncryptedPasswd($this->config->initiatorPassword);

        $phone = $this->isLive() ? format_safaricom_number($phone) : "254708374149";

        $requestData = array(
            "InitiatorName" => $this->config->initiatorName,
            "SecurityCredential" => $encryptedPassword,
            "CommandID" => $command,
            "Amount" => $amount,
            "PartyA" => $this->config->shortCode,
            "PartyB" => $phone,
            "Remarks" => $remarks,
            "QueueTimeOutURL" => $this->config->timeOutUrl,
            "ResultURL" => $this->config->resultsUrl,
            "Occasion" => $occasion,
        );

        try {
            $response = $this->getClient()->post('mpesa/b2c/v1/paymentrequest', [
                'json' => $requestData,
                'headers' => [
                    "Authorization" => "Bearer $token",
                ]
            ]);

            $response = $response->getBody()->getContents();

            /** @var B2CSuccessfulResponseResult $result */

            $result = B2CSuccessfulResponseResult::fromResponseObject(json_decode($response));

            return BaseDarajaResponse::successful($result->ResponseDescription, $result);


        } catch (BadResponseException $e) {

            $errorResponse = $e->getResponse()->getBody()->getContents();

            $errorResponse =  BaseErrorResponseResult::fromResponseObject(json_decode($errorResponse));

            return BaseDarajaResponse::failed($errorResponse->errorMessage,data: $errorResponse);
        } catch (Exception|GuzzleException $e) {
            return BaseDarajaResponse::failed($e->getMessage(),data:null);

        }
    }
}
