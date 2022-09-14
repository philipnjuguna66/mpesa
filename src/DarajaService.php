<?php


namespace Rickodev\Mpesa;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Rickodev\Mpesa\Configs\BaseConfig;

abstract class DarajaService
{
    protected BaseConfig $config;

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->getBaseUrl(),
            'timeout' => 2.0,
            'http_errors' => true,
            'headers' => [
                "Content-Type" => ["application/json"],
            ]
        ]);

    }

    protected function getBaseUrl(): string
    {
        return $this->isLive()
            ? "https://api.safaricom.co.ke/"
            : "https://sandbox.safaricom.co.ke/";
    }

    protected function isLive(): bool
    {
        return $this->config->environment === 'live';
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }


    protected function getToken(): string
    {
        $credentials = $this->getCredentials();

        try {

            $response = $this->client->get('oauth/v1/generate?grant_type=client_credentials',
                [
                    'headers' => [
                        "Authorization" => "Basic $credentials",
                        "Content-Type" => "application/json",
                    ]
                ]);

            $contents = $response->getBody()->getContents();

            $responseObj = json_decode($contents);


            return $responseObj->access_token;

        } catch (ClientException $e) {

            return "";
        }
    }


    private function getCredentials(): string
    {
        $credentials = sprintf("%s:%s", $this->config->key, $this->config->secret);

        return base64_encode($credentials);

    }


    protected function getSandBoxEncryptedPasswd($plaintext)
    {
        $pk = $this->getSandBoxPublicKey();
        openssl_public_encrypt($plaintext, $encrypted, $pk, OPENSSL_PKCS1_PADDING);
        return base64_encode($encrypted);
    }

    protected function getSandBoxPublicKey(): bool|string
    {
        return file_get_contents(__DIR__ . './Configs/mpesa_public_cert.cer');
    }

    protected function getLiveEncryptedPasswd($plaintext)
    {
        $pk = $this->getLivePublicKey();
        openssl_public_encrypt($plaintext, $encrypted, $pk, OPENSSL_PKCS1_PADDING);
        return base64_encode($encrypted);
    }

    protected function getLivePublicKey(): bool|string
    {
        return file_get_contents(__DIR__ . './Configs/mpesa_public_cert.cer');
    }

}
