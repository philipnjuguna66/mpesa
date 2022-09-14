<?php

namespace Rickodev\Mpesa\Configs;

class C2BConfig extends BaseConfig
{
    public string $shortCode;
    public string $confirmationUrl;
    public string $validationUrl;
    public string $lnmPassKey;
    public string $stkCallBackUrl;
    public int $version = 1;

}
