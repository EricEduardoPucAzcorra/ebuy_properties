<?php

namespace App\Helpers;

use Openpay\Data\Openpay;

class OpenpayHelper
{
    public static function getInstance($clientIp = null)
    {
        $isProduction = config('app.env') === 'production';
        
        if (!$clientIp || in_array($clientIp, ['127.0.0.1', '::1', ''])) {
            $clientIp = '187.189.155.42';
        }
        
        return Openpay::getInstance(
            env('OPENPAY_MERCHANT_ID'),
            env('OPENPAY_PRIVATE_KEY'),
            'MX',
            $clientIp,
            $isProduction
        );
    }
}