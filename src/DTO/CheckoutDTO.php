<?php

namespace Sumup\Laravel\DTO;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class CheckoutDTO extends Data
{
    public function __construct(
        public float $amount,
        public string $currency,
        #[MapInputName('checkout_reference')]
        public ?string $checkoutReference = null,
        public ?string $description = null,
        public ?array $metadata = null,
    ) {
    }
} 