<?php


namespace Rickodev\Mpesa;



use GuzzleHttp\Exception\GuzzleException;

use Rickodev\Mpesa\Configs\B2cConfig;

class DarajaB2C extends DarajaService
{
    protected Configs\BaseConfig $config;

    public function __construct(private B2cConfig $configuration,array $overrides = [])
    {
        $this->config = $this->configuration;

        parent::__construct($overrides);
    }

    /**
     * @throws GuzzleException
     */
    public function send(string $phone, int $amount = 1, string $remarks = "Here are my remarks", string $occasion = "Payment", string $command = "BusinessPayment"): \Psr\Http\Message\ResponseInterface
    {
        $token = $this->getToken();

        $encryptedPassword = $this->isLive() ? $this->getLiveEncryptedPasswd($this->config->initiatorPassword) : $this->getSandBoxEncryptedPasswd($this->config->initiatorPassword);

        $phone = $this->isLive() ? format_safaricom_number($phone) : "254708374149";

        $requestData = array(
            "InitiatorName" => $this->config->initiatorName,
            "SecurityCredential" => $encryptedPassword,
            "CommandID" => $command,
            "Amount" => round($amount),
            "PartyA" => $this->config->shortCode,
            "PartyB" => $phone,
            "Remarks" => $remarks,
            "QueueTimeOutURL" => $this->config->timeOutUrl,
            "ResultURL" => $this->config->resultsUrl,
            "Occasion" => $occasion,
        );

        return $this->getClient()->post('mpesa/b2c/v1/paymentrequest', [
            'json' => $requestData,
            'headers' => [
                "Authorization" => "Bearer $token",
            ]
        ]);
    }
}
