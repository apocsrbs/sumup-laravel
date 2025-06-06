<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;

class WebhookService extends HttpClient
{
    public function register(array $data)
    {
        return $this->post('/v0.1/webhooks', $data);
    }

    public function list()
    {
        return $this->get('/v0.1/webhooks');
    }

    public function delete($webhookId)
    {
        return $this->delete("/v0.1/webhooks/{$webhookId}");
    }
}
