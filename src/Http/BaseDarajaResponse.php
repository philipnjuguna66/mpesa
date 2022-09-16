<?php

namespace Rickodev\Mpesa\Http;


use Rickodev\Mpesa\Results\BaseResult;

class BaseDarajaResponse implements IDarajaResponse
{

    public function __construct(public string  $message, public ?BaseResult  $data , public bool $status = true)
    {
    }

    public static function successful(string $message,BaseResult $data): self
    {
        return new static(message:$message,data:$data,status: true);
    }

    public static function failed(string $message,?BaseResult  $data):self
    {
        return new static(message:$message,data:$data,status:false);
    }
}
