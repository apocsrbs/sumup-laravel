<?php

namespace Sumup\Laravel\Http;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Client\Response;
use Sumup\Laravel\Exceptions\SumupApiException;

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

    /**
     * @throws SumupApiException
     */
    protected function get(string $endpoint, array $params = [])
    {
        try {
            $headers = $this->getHeaders();
            
            // Debug request
            logger()->debug('SumUp API Request', [
                'method' => 'GET',
                'endpoint' => $endpoint,
                'url' => $this->baseUrl . $endpoint,
                'headers' => $headers,
                'data' => $params
            ]);

            $response = Http::withHeaders($headers)
                ->get($this->baseUrl . $endpoint, $params)
                ->throw();

            // Debug response
            logger()->debug('SumUp API Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleRequestException($e);
            return []; // Dette vil aldrig blive nået, da handleRequestException altid kaster en exception
        } catch (\Exception $e) {
            throw new SumupApiException(
                'Der opstod en uventet fejl ved kommunikation med Sumup API: ' . $e->getMessage()
            );
        }
    }

    /**
     * @throws SumupApiException
     */
    protected function post(string $endpoint, array $data = [])
    {
        try {
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
                ->post($this->baseUrl . $endpoint, $data)
                ->throw();

            // Debug response
            logger()->debug('SumUp API Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleRequestException($e);
            return []; // Dette vil aldrig blive nået, da handleRequestException altid kaster en exception
        } catch (\Exception $e) {
            throw new SumupApiException(
                'Der opstod en uventet fejl ved kommunikation med Sumup API: ' . $e->getMessage()
            );
        }
    }

    /**
     * @throws SumupApiException
     */
    protected function put(string $endpoint, array $data = []): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put($this->baseUrl . $endpoint, $data)
                ->throw();

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleRequestException($e);
            return []; // Dette vil aldrig blive nået, da handleRequestException altid kaster en exception
        } catch (\Exception $e) {
            throw new SumupApiException(
                'Der opstod en uventet fejl ved kommunikation med Sumup API: ' . $e->getMessage()
            );
        }
    }

    /**
     * @throws SumupApiException
     */
    protected function patch(string $endpoint, array $data = []): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->patch($this->baseUrl . $endpoint, $data)
                ->throw();

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleRequestException($e);
            return []; // Dette vil aldrig blive nået, da handleRequestException altid kaster en exception
        } catch (\Exception $e) {
            throw new SumupApiException(
                'Der opstod en uventet fejl ved kommunikation med Sumup API: ' . $e->getMessage()
            );
        }
    }

    /**
     * @throws SumupApiException
     */
    protected function delete(string $endpoint): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete($this->baseUrl . $endpoint)
                ->throw();

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleRequestException($e);
            return []; // Dette vil aldrig blive nået, da handleRequestException altid kaster en exception
        } catch (\Exception $e) {
            throw new SumupApiException(
                'Der opstod en uventet fejl ved kommunikation med Sumup API: ' . $e->getMessage()
            );
        }
    }

    /**
     * @throws SumupApiException
     */
    private function handleRequestException(\Illuminate\Http\Client\RequestException $e): void
    {
        $response = $e->response;
        
        if (!$response instanceof Response) {
            throw new SumupApiException(
                'Error communicating with Sumup API: ' . $e->getMessage()
            );
        }

        $statusCode = $response->status();
        $responseData = $response->json() ?? [];

        // Log fejlen
        logger()->error('SumUp API Error', [
            'status' => $statusCode,
            'response' => $responseData,
            'message' => $e->getMessage()
        ]);

        throw SumupApiException::fromResponse($responseData, $statusCode);
    }
} 