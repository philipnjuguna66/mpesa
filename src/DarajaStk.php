<?php


namespace Rickodev\Mpesa;




use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Rickodev\Mpesa\Configs\C2BConfig;
use Rickodev\Mpesa\Http\BaseDarajaResponse;
use Rickodev\Mpesa\Results\LNM\STkResponseResult;

class DarajaStk extends DarajaService
{
    protected Configs\BaseConfig $config;


    public function __construct(private C2BConfig $configuration)
    {
        $this->config = $this->configuration;

        parent::__construct();
    }



    public function send(string $phone, int $amount, string $reference = "test", string $description = "Transaction Description", string $remark = "Remarks",string $transactionType = "CustomerPayBillOnline"):BaseDarajaResponse
    {

        $phone = format_safaricom_number($phone);

        $timestamp = date("YmdHis");

        $password = base64_encode($this->config->shortCode . $this->config->lnmPassKey . $timestamp);

        $token = $this->getToken();


        $requestData = array(
            "BusinessShortCode" => $this->config->shortCode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" =>$transactionType,
            "Amount" => $amount,
            "PartyA" => $phone,
            "PartyB" => $this->config->shortCode,
            "PhoneNumber" => $phone,
            "CallBackURL" => $this->config->stkCallBackUrl,
            "AccountReference" => $reference,
            "TransactionDesc" => $description,
            "Remark" => $remark,
        );

        try {
            $response = $this->getClient()->post("mpesa/stkpush/v1/processrequest", [
                'json' => $requestData,
                'headers' => [
                    "Authorization" => "Bearer $token",
                ]
            ]);

            $response = $response->getBody()->getContents();

            /** @var STkResponseResult $result */

            $result = STkResponseResult::fromResponseObject(json_decode($response));

            return BaseDarajaResponse::successful($result->ResponseDescription, $result);

        } catch (BadResponseException $e) {

            return BaseDarajaResponse::failed($e->getResponse()->getReasonPhrase(),data:null);

        } catch (Exception|GuzzleException $e) {

            return BaseDarajaResponse::failed($e->getMessage(),data:null);

        }
    }
}
