<?php

namespace App\Services\Bbva;

use App\Services\Bbva\BbvaHttpClient;
use App\Services\Bbva\ChargeService;

class BbvaAPI
{
    public $ChargeService;
    private $httpClient;

    public function __construct(string $api_key, string $merchant_id, bool $production = false)
    {
        $this->httpClient = new BbvaHttpClient($api_key, $merchant_id, $production);
        $this->ChargeService = new ChargeService($this->httpClient);
    }

    public function getProduction(): bool
    {
        return $this->httpClient->isProduction();
    }
}
