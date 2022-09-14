<?php

namespace Rickodev\Mpesa\Configs;

class C2BConfig extends  BaseConfig
{

    public function __construct(public string $key,public string $secret,public string $shortCode,public string $confirmationUrl,public string $validationUrl,public string $lnmPassKey,public string $stkCallBackUrl, public int $version = 1,public string $environment = 'sandbox')
    {
        parent::__construct(key:$key,secret: $secret,environment: $environment);

    }

}
