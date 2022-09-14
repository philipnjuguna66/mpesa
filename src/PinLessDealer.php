<?php

namespace Rickodev\Mpesa;




use GuzzleHttp\Exception\GuzzleException;
use Rickodev\Mpesa\Configs\PinLessDealerConfig;

class  PinLessDealer extends DarajaService
{

    protected Configs\BaseConfig $config;

    public function __construct(private PinLessDealerConfig $configuration,array $overrides = [])
    {
        $this->config = $this->configuration;

        parent::__construct($overrides);

    }

    /**
     * @throws GuzzleException
     */
    public function c2sRecharge(string $phoneNumber, int $amountInKsh): \Psr\Http\Message\ResponseInterface
    {
        $requestData = [
            'amount' => $amountInKsh * 100, //convert to cents
            'senderMsisdn' => $this->config->senderMsisdn, // 748248717
            'servicePin' => base64_encode($this->config->servicePin), // 9090
            'receiverMsisdn' => $phoneNumber,
        ];

        $token = $this->getToken();

        return $this->getClient()->post('v1/pretups/api/recharge', [
            'json' => $requestData,
            'headers' => [
                "Authorization" => "Bearer $token",
            ]
        ]);
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
