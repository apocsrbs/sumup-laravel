<?php

namespace Sumup\Laravel\Services;

use Sumup\Laravel\Http\HttpClient;

class CheckoutService extends HttpClient
{

    public function all($checkout_reference = null)
    {
        return $this->get('/v0.1/checkouts', $checkout_reference ? ['checkout_reference' => $checkout_reference] : []);
    }

    public function create(array $data)
    {
        $data['merchant_code'] = $this->merchantId;

        return $this->post('/v0.1/checkouts', $data);
    }

    public function getCheckout($checkoutId)
    {
        return $this->get("/v0.1/checkouts/{$checkoutId}");
    }

    public function update($checkoutId, array $data)
    {
        return $this->put("/v0.1/checkouts/{$checkoutId}", $data);
    }

    public function destroy($checkoutId)
    {
        return $this->delete("/v0.1/checkouts/{$checkoutId}");
    }
}
