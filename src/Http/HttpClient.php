<?php

namespace Sumup\Laravel\Http;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class HttpClient
{
    protected ?string $token;
    protected ?string $merchantId;
    protected ?string $apiKey;
    protected string $baseUrl;

    public function __construct(?string $token = null, ?string $merchantId = null, ?string $apiKey = null)
    {
        $this->token = $token;
        $this->merchantId = $merchantId;
        $this->apiKey = $apiKey;
        $this->baseUrl = Config::get('sumup.api_base', 'https://api.sumup.com');
    }

    protected function getHeaders(): array
    {
        $headers = [];
        
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        if ($this->apiKey) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        return $headers;
    }

    protected function get(string $endpoint, array $params = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . $endpoint, $params)
            ->json();
    }

    protected function post(string $endpoint, array $data = [])
    {
        $headers = $this->getHeaders();
        
        // Debug request
        logger()->debug('SumUp API Request', [
            'method' => 'POST',
            'endpoint' => $endpoint,
            'url' => $this->baseUrl . $endpoint,
            'headers' => $headers,
            'data' => $data
        ]);

        $response = Http::withHeaders($headers)
            ->post($this->baseUrl . $endpoint, $data);

        // Debug response
        logger()->debug('SumUp API Response', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);

        return $response->json();
    }

    protected function put(string $endpoint, array $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->put($this->baseUrl . $endpoint, $data)
            ->json();
    }

    protected function delete(string $endpoint)
    {
        return Http::withHeaders($this->getHeaders())
            ->delete($this->baseUrl . $endpoint)
            ->json();
    }
} 