<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;

class PayoutService extends HttpClient
{
    public function all(array $params = [])
    {
        return $this->get('/v0.1/me/payouts', $params);
    }

    public function getPayout($payoutId)
    {
        return $this->get("/v0.1/me/payouts/{$payoutId}");
    }
}
