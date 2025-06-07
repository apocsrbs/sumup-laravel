<?php

namespace Sumup\Laravel;

use Sumup\Laravel\Services\OAuthService;
use Sumup\Laravel\Services\CheckoutService;
use Sumup\Laravel\Services\TransactionService;
use Sumup\Laravel\Services\ReaderService;
use Sumup\Laravel\Services\ReceiptService;
use Sumup\Laravel\Services\PayoutService;
use Sumup\Laravel\Services\WebhookService;

class SumUpClient
{
    protected ?string $clientId;
    protected ?string $clientSecret;
    protected ?string $redirectUri;
    protected ?string $merchantId;
    protected ?string $apiKey;

    public function __construct(?string $clientId, ?string $clientSecret, ?string $redirectUri, ?string $merchantId = null, ?string $apiKey = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->merchantId = $merchantId;
        $this->apiKey = $apiKey;
    }

    public function oauth(): OAuthService
    {
        return new OAuthService($this->clientId, $this->clientSecret, $this->redirectUri);
    }

    public function checkouts(?string $accessToken = null): CheckoutService
    {
        return new CheckoutService($accessToken, $this->merchantId, $this->apiKey);
    }

    public function transactions(?string $accessToken = null): TransactionService
    {
        return new TransactionService($accessToken, $this->merchantId, $this->apiKey);
    }

    public function readers(?string $accessToken = null): ReaderService
    {
        return new ReaderService($accessToken, $this->merchantId, $this->apiKey);
    }

    public function receipts(?string $accessToken = null): ReceiptService
    {
        return new ReceiptService($accessToken, $this->merchantId, $this->apiKey);
    }

    public function payouts(?string $accessToken = null): PayoutService
    {
        return new PayoutService($accessToken, $this->merchantId, $this->apiKey);
    }

    public function webhooks(?string $accessToken = null): WebhookService
    {
        return new WebhookService($accessToken, $this->merchantId, $this->apiKey);
    }
}
