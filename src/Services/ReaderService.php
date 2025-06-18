<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;
use Sumup\Laravel\DTO\ReaderDTO;
use Sumup\Laravel\DTO\ReaderCollectionDTO;
use Sumup\Laravel\DTO\CheckoutDTO;
use Sumup\Laravel\Exceptions\SumupApiException;
use Illuminate\Http\Client\Response;

class ReaderService extends HttpClient
{
    /**
     * @throws SumupApiException
     */
    public function all(): ReaderCollectionDTO
    {
        try {
            $response = $this->get('/v0.1/merchants/' . $this->merchantId . '/readers');

            return ReaderCollectionDTO::from($response);
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * @throws SumupApiException
     */
    public function getReader(string $reader_id): ReaderDTO
    {
        try {
            $response = $this->get("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}");
            return ReaderDTO::from($response);
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * @throws SumupApiException
     */
    public function create(array $data): ReaderDTO
    {
        try {
            $response = $this->post('/v0.1/merchants/' . $this->merchantId . '/readers', $data);
            return ReaderDTO::from($response);
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
    public function checkout(string $reader_id, CheckoutDTO $checkoutData): array
    {
        try {
            return $this->post(
                "/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}/checkout",
                $checkoutData->toArray()
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
