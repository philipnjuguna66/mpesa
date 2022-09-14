<?php

namespace Rickodev\Mpesa\Configs;

abstract class BaseConfig
{
    public string $environment = 'sandbox';
    public string $key;
    public string $secret;

}
