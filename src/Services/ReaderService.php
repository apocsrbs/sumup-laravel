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
    public function update(string $reader_id, array $data)
    {
        try {
            $response = $this->patch("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}", $data);
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
            // Terminate svarer 202 med tom body — der er ingen bekræftelse i svaret.
            // Kaldet er lykkedes hvis der ikke blev kastet en exception.
            $this->post("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}/terminate");

            return true;
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    public function getStatus(string $reader_id): array
    {
        try {
            $response = $this->get("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}/status");
            return $response;
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * Håndterer exceptions fra API kald
     */
    private function handleException(\Exception $e): SumupApiException
    {
        // HttpClient har allerede oversat HTTP-fejlen. Pakker vi den ind igen,
        // mister vi statuskoden og SumUps egen fejlbesked.
        if ($e instanceof SumupApiException) {
            return $e;
        }

        if ($e instanceof \Illuminate\Http\Client\RequestException) {
            $response = $e->response;
            if ($response instanceof Response) {
                $responseData = $this->normalizeReaderErrors($response->json() ?? []);

                return SumupApiException::fromResponse(
                    $responseData,
                    $response->status()
                );
            }
        }

        return new SumupApiException(
            'Error communicating with Sumup API: ' . $e->getMessage()
        );
    }

    private function normalizeReaderErrors(array $responseData): array
    {
        if (isset($responseData['response']) && is_array($responseData['response'])) {
            $responseData = $responseData['response'];
        }

        $errors = $responseData['errors'] ?? null;

        if (is_array($errors)) {
            $detail = $errors['detail'] ?? $errors['message'] ?? $responseData['message'] ?? null;

            if (is_string($detail) && $detail !== '') {
                $responseData['message'] = $detail;
            }
        }

        return $responseData;
    }
}
