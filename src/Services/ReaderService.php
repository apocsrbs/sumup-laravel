<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;
use Sumup\Laravel\Exceptions\SumupApiException;
use Illuminate\Http\Client\Response;

class ReaderService extends HttpClient
{
    /**
     * @throws SumupApiException
     */
    public function all()
    {
        try {
            $response = $this->get('/v0.1/merchants/' . $this->merchantId . '/readers');

            return $response;
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * @throws SumupApiException
     */
    public function getReader(string $reader_id)
    {
        try {
            $response = $this->get("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}");
            return $response;
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * @throws SumupApiException
     */
    public function create(array $data)
    {
        try {
            $response = $this->post('/v0.1/merchants/' . $this->merchantId . '/readers', $data);
            return $response;
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * @throws SumupApiException
     */
    public function destroy(string $reader_id): bool
    {
        try {
            $response = $this->delete("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}");
            return $response['success'] ?? false;
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * @throws SumupApiException
     */
    public function checkout(string $reader_id, $checkoutData): array
    {
        try {
            return $this->post(
                "/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}/checkout",
                $checkoutData
            );
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * @throws SumupApiException
     */
    public function cancelCheckout(string $reader_id): bool
    {
        try {
            $response = $this->post("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}/terminate");
            return $response['success'] ?? false;
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * Håndterer exceptions fra API kald
     */
    private function handleException(\Exception $e): SumupApiException
    {
        if ($e instanceof \Illuminate\Http\Client\RequestException) {
            $response = $e->response;
            if ($response instanceof Response) {
                return SumupApiException::fromResponse(
                    $response->json() ?? [],
                    $response->status()
                );
            }
        }

        return new SumupApiException(
            'Error communicating with Sumup API: ' . $e->getMessage()
        );
    }
}
