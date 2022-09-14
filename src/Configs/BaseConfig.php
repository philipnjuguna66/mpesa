<?php

namespace Rickodev\Mpesa\Configs;

abstract class BaseConfig
{
    public function __construct(public string $key,public string $secret,public string $environment = 'sandbox')
    {
    }

}
