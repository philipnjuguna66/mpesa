<?php

namespace Rickodev\Mpesa\Http;


use Rickodev\Mpesa\Results\BaseResult;

interface IDarajaResponse
{

    public static function successful(string $message,BaseResult $data);
    public static function failed(string $message,?BaseResult  $data);

}
