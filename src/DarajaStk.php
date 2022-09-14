<?php


namespace Rickodev\Mpesa;




use Rickodev\Mpesa\Configs\C2BConfig;

class DarajaStk extends DarajaService
{
    protected Configs\BaseConfig $config;


    public function __construct(private C2BConfig $configuration)
    {
        $this->config = $this->configuration;

        parent::__construct();
    }


    public function send(string $phone, int $amount, string $reference = "test", string $description = "Transaction Description", string $remark = "Remarks"): \Psr\Http\Message\ResponseInterface
    {

        $phone = (str_starts_with($phone, "+")) ? str_replace("+", "", $phone) : $phone;
        $phone = (str_starts_with($phone, "0")) ? preg_replace("/^0/", "254", $phone) : $phone;
        $phone = (str_starts_with($phone, "7")) ? "254{$phone}" : $phone;

        $timestamp = date("YmdHis");

        $password = base64_encode($this->config->shortCode . $this->config->lnmPassKey . $timestamp);

        $token = $this->getToken();

        $requestData = array(
            "BusinessShortCode" => $this->config->shortCode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => round($amount),
            "PartyA" => $phone,
            "PartyB" => $this->config->shortCode,
            "PhoneNumber" => $phone,
            "CallBackURL" => $this->config->stkCallBackUrl,
            "AccountReference" => $reference,
            "TransactionDesc" => $description,
            "Remark" => $remark,
        );

        return $this->getClient()->post('mpesa/stkpush/v1/processrequest', [
            'json' => $requestData,
            'headers' => [
                "Authorization" => "Bearer $token",
            ]
        ]);
    }
}
