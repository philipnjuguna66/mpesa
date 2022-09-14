<?php

namespace Rickodev\Mpesa\Configs;

 class B2cConfig extends BaseConfig
{
     public function __construct(string $key, string $secret, public string $shortCode,public string $resultsUrl,public string $timeOutUrl,public string $initiatorName,      public string $initiatorPassword,string $environment = 'sandbox')
     {
         parent::__construct(key:$key,secret: $secret,environment: $environment);

     }
 }
