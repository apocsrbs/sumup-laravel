<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;

class TransactionService extends HttpClient
{
    public function all(array $params = [])
    {
        return $this->get('/v2.1/merchants/' . $this->merchantId . '/transactions/history', $params);
    }

    public function getTransaction($data)
    {
        return $this->get("/v2.1/merchants/" . $this->merchantId . "/transactions", $data);
    }

    public function refund($transaction_id, $amount)
    {
        return $this->post("/v0.1/me/refund/{$transaction_id}", [
            'amount' => $amount
        ]);
    }
}
