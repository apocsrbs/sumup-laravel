<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;

class ReceiptService extends HttpClient
{
    public function getReceipt($transactionCode)
    {
        return $this->get("/v0.1/receipts/{$transactionCode}");
    }
}
