<?php


if(!function_exists('format_safaricom_number')){


    function format_safaricom_number(string $phone): string
    {
        $phone = str_starts_with($phone, "+") ? str_replace("+", "", $phone) : $phone;
        $phone = (str_starts_with($phone, "0")) ? preg_replace("/^0/", "254", $phone) : $phone;
        return (str_starts_with($phone, "7")) ? "254{$phone}" : $phone;
    }

}
