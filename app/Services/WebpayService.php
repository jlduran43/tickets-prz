<?php

namespace App\Services;

use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus\Transaction;

class WebpayService
{
    public function transaction(): Transaction
    {
        $environment =
            config('services.webpay.environment') === 'production'
                ? Options::ENVIRONMENT_PRODUCTION
                : Options::ENVIRONMENT_INTEGRATION;

        $options = new Options(
            config('services.webpay.api_key'),
            config('services.webpay.commerce_code'),
            $environment
        );

        return new Transaction($options);
    }
}