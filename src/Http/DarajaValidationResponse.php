<?php

namespace Rickodev\Mpesa\Http;

use GuzzleHttp\Psr7\Response;

class DarajaValidationResponse
{


    public static function accept(): Response
    {

       return new Response(status:200,headers: ['Content-type' => 'application/json; charset=utf-8'],body: json_encode(['ResultCode' => 0, 'ResultDesc' => 'Accepted']));

    }

    public static function reject(): Response
    {

        return new Response(status:200,headers: ['Content-type' => 'application/json; charset=utf-8'],body: json_encode(['ResultCode' => 'C2B00011', 'ResultDesc' => 'Rejected']));
    }

}
