<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;

class ReaderService extends HttpClient
{
    public function all()
    {
        return $this->get('/v0.1/merchants/' . $this->merchantId . '/readers');
    }

    public function getReader($reader_id)
    {
        return $this->get("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}");
    }

    public function create(array $data)
    {
        return $this->post('/v0.1/merchants/' . $this->merchantId . '/readers', $data);
    }

    public function delete($reader_id)
    {
        return $this->delete("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}");
    }

    public function checkout($reader_id, $data)
    {
        return $this->post("/v0.1/merchants/" . $this->merchantId . "/readers/{$reader_id}/checkout", $data);
    }
}
