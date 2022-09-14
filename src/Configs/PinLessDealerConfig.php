<?php

namespace Rickodev\Mpesa\Configs;

class PinLessDealerConfig extends BaseConfig
{
   public function __construct(string $key, string $secret,  public string $servicePin,public string $senderMsisdn, string $environment = 'sandbox')
   {

       parent::__construct(key:$key,secret: $secret,environment: $environment);
   }
}
