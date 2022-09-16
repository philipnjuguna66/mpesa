<?php

namespace Rickodev\Mpesa\Results;

interface BaseResult
{

    public static function fromResponseObject(object $responseBody): self;
    public static function fromResponseArray(array $responseBody):self;
    public function toArray():array;


}
